<?php

//要 composer require intervention/image

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageCompressor
{
    public static function compressAndStore(
        UploadedFile $file,
        string $path,
        string $disk = 'public_fileassets',
        int $maxKb = 40
    ): string {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getPathname());

        // サイズが大きすぎる場合はリサイズ（任意）
        $image->scaleDown(width: 1200);

        $quality = 90;
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
