<template>
  <div>
    <v-btn
      color="primary"
      @click="dialog = true"
    >
      ダイアログを開く
    </v-btn>

    <v-dialog
      v-model="dialog"
      width="700"
    >
      <v-card>
        <v-card-title class="text-body-1 text-sm-body-2 text-md-h6">
          Safarigamesからのお知らせ
        </v-card-title>

        <v-card-text>
          <!-- カスタムHTMLコンテンツ -->
          <div class="custom-content">
            <div class="header-section">
              <h2 class="text-body-1 text-sm-body-2 text-md-h6 line-height-2">
                モストラは<br>
                Safaruganes が提供するBtoBサービスです
              </h2>
            </div>
            <div class="content-grid">
              <div class="grid-item">
                <img src="/api/placeholder/200/200" alt="サンプル画像">
                <h3>簡単操作でカタログ、商品を公開</h3>
              </div>

              <div class="grid-item">
                <img src="/api/placeholder/200/200" alt="サンプル画像">
                <h3>商品の見積依頼、受発注が可能</h3>
              </div>
            </div>
           <!-- <v-row class="mb-6">
              <v-col cols="6">
                <div class="grid-item">
                  <v-img
                    src="/api/placeholder/300/300"
                    max-width="300"
                    class="mx-auto"
                  ></v-img>
                  <h3 class="mt-4">簡単操作でカタログ、商品を公開</h3>
                </div>
              </v-col>

              <v-col cols="6">
                <div class="grid-item">
                  <v-img
                    src="/api/placeholder/300/300"
                    max-width="300"
                    class="mx-auto"
                  ></v-img>
                  <h3 class="mt-4">商品の見積依頼、受発注が可能</h3>
                </div>
              </v-col>
            </v-row> -->
            <div class="text-center">
                <p class="text-body-1 text-sm-body-2 text-md-h6">
                  初期費用¥0<br>
                  31日間無料でお試しできます
                </p>
                <div class="my-2">
                  <v-btn
                    class="mb-4"
                    large
                    color="primary"
                    hide-details
                    dark
                    >
                  詳しくはこちら
                  </v-btn>
                </div>
              <p>お気軽にお問い合わせください</p>
              <p>Powered by mostra</p>
              <p>https://mostra-web.com</p>
              <div class="text-center">
                <v-checkbox
                  v-model="ex4"
                  label="今後このお知らせを表示しない"
                  color="indigo"
                  value="indigo"
                  hide-details
                  class="d-inline-flex mb-4"
                  dense
                ></v-checkbox>
              </div>
              <p>閉じる</p>
            </div>
          </div>
        </v-card-text>

      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: 'DialogExample',
  data() {
    return {
      dialog: false
    }
  }
}
</script>

<style scoped>
.custom-content {
  padding: 20px;
  font-family: Arial, sans-serif;
}

.header-section {
  text-align: center;
  margin-bottom: 30px;
}

.header-section h2 {
  color: #333;
  font-size: 28px;
  margin-bottom: 10px;
}

.grid-item {
  background: #f5f5f5;
  padding: 15px;
  border-radius: 8px;
  text-align: center;
}

.grid-item h3 {
  color: #2c3e50;
  margin-bottom: 10px;
}

.d-inline-flex ::v-deep .v-label {
  font-size: 14px !important;
}

/* レスポンシブ対応 */
@media (max-width: 600px) {
  .header-section h2 {
    font-size: 24px;
  }
}

/* チェックボックスのスタイル */
.caption-checkbox {
  margin-top: 0;
  margin-bottom: 0;
}

.caption-checkbox ::v-deep .v-label {
  font-size: 10px !important;
  white-space: nowrap;
  margin-right: 0;
}

.caption-checkbox ::v-deep .v-input--selection-controls__input {
  transform: scale(0.8);
  margin-right: 0;
}
</style>
