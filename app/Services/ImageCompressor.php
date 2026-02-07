<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageCompressor
{
    public static function compressAndStore(
        UploadedFile $file,
        string $path,
        string $disk = 'public_fileassets',
        int $maxKb = 60
    ): string {
        // 🔒 画像以外は拒否
        if (! Str::startsWith($file->getMimeType(), 'image/')) {
            throw new \InvalidArgumentException('画像ファイルのみ対応しています');
        }

        // 🔒 解像度チェック（先に）
        [$width, $height] = getimagesize($file->getPathname());

        if ($width > 6000 || $height > 6000) {
            throw new \InvalidArgumentException('画像の解像度が大きすぎます');
        }

        // ここから重い処理
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getPathname());

        // サイズが大きすぎる場合はリサイズ
        $image->scaleDown(width: 1200);
        $quality  = 90;
        $maxBytes = $maxKb * 1024;

        do {
            $encoded = $image->toJpeg($quality);
            $quality -= 5;
        } while ($encoded->size() > $maxBytes && $quality > 10);

        $filename = uniqid() . '.jpg';
        $fullPath = $path . '/' . $filename;

        Storage::disk($disk)->put($fullPath, (string) $encoded);

        return $fullPath;
    }
}
