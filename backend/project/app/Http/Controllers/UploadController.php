<?php

namespace App\Http\Controllers;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'imagesZip' => 'required|file|mimes:zip|max:10240',
        ]);

        if (!$request->file('imagesZip')->isValid()) {
            return response()->json(['error' => 'アップロードに失敗しました'], 400);
        }

        $file = $request->file('imagesZip');
        $fileName = time() . '_' . $file->getClientOriginalName();

        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        try {
            $result = $s3->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => 'uploads/' . $fileName,
                'Body'   => fopen($file->getRealPath(), 'r'),
                'ACL'    => 'private',
            ]);

            return response()->json(['message' => 'ファイルがアップロードされました', 'path' => $result['ObjectURL']]);
        } catch (AwsException $e) {
            Log::error('AWS Error: ' . $e->getMessage(), [
                'code' => $e->getAwsErrorCode(),
                'type' => $e->getAwsErrorType(),
                'requestId' => $e->getAwsRequestId(),
            ]);
            return response()->json(['error' => 'アップロードに失敗しました: ' . $e->getMessage()], 500);
        }
    }
}
