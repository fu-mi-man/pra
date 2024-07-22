<?php

$host = 'db';
$dbname = 'laravel';
$username = 'user';
$password = 'pass';

try {
    $dbh = connect_mysql($host, $dbname, $username, $password);

    $productImageIds = getProductImageIds($dbh);
    if (!empty($productImageIds)) {
        $allProductImageIds = createAllProductImageIds($productImageIds);
        $updatedRows = updateImageTypes($dbh, $allProductImageIds, 'product');
        echo "Total updated rows in images table: $updatedRows" . PHP_EOL;
    } else {
        echo "No product image IDs found." . PHP_EOL;
    }

    // カタログ画像の処理
    $catalogImageIds = getCatalogImageIds($dbh);
    if (!empty($catalogImageIds)) {
        $updatedRows = updateImageTypes($dbh, $catalogImageIds, 'catalog');
        echo "Total updated rows for catalog images: $updatedRows" . PHP_EOL;
    } else {
        echo "No catalog image IDs found." . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
} finally {
    $dbh = null; // 接続を閉じる
}

/**
 * PDOでMySQLに接続する
 */
function connect_mysql($host, $dbname, $username, $password)
{
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラー時に例外をスロー
        return $pdo;
    } catch (PDOException $e) {
        echo $e->getMessage() . PHP_EOL;
        throw new Exception("Failed to connected database!");
    }
}

/**
 * product_imagesテーブルからimage_idを取得する
 */
function getProductImageIds($dbh)
{
    $sql = <<<SQL
    SELECT image_id FROM product_images
    SQL;
    $stmt = $dbh->prepare($sql);
    if (!$stmt->execute()) {
        throw new Exception("getProductImageIds: Failed to execute query!");
    }

    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // 一次配列
}

/**
 * catalog_pagesテーブルからimage_idを取得する
 */
function getCatalogImageIds($dbh)
{
    $sql = "SELECT image_id FROM catalog_pages";
    $stmt = $dbh->prepare($sql);
    if (!$stmt->execute()) {
        throw new Exception("getCatalogImageIds: Failed to execute query!");
    }

    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // 一次配列
}

/**
 *
 */
function createAllProductImageIds($productImageIds)
{
    $allProductImageIds = [];
    foreach ($productImageIds as $imageId) {
        $allProductImageIds[] = $imageId;
        $allProductImageIds[] = $imageId . '.thumbnail';
    }
    return $allProductImageIds;
}

/**
 * typeを更新する
 */
function updateImageTypes($dbh, $allImageIds, $type)
{
    $chunkSize = 10;
    $totalUpdatedRows = 0;
    foreach (array_chunk($allImageIds, $chunkSize) as $chunk) {
        $imageIdsString = "'" . implode("','", $chunk) . "'";
        $updateSql = "UPDATE images SET type = '$type' WHERE id IN ($imageIdsString)";
        $affectedRows = $dbh->exec($updateSql);
        $totalUpdatedRows += $affectedRows;
        echo "Updated $affectedRows rows in images table." . PHP_EOL;
    }

    return $totalUpdatedRows;
}
