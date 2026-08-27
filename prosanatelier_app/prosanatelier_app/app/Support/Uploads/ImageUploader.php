<?php

namespace App\Support\Uploads;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageUploader
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
    private const RASTER_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    public static function store(UploadedFile $file, string $folder): string
    {
        self::validateUpload($file);

        $folder = trim($folder, '/');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = now()->format('YmdHis') . '-' . Str::random(24) . '.' . $extension;

        // On this cPanel setup the real public document root is beside the Laravel app.
        // Store there when possible so uploaded images are served without relying on symlinks.
        $livePublicRoot = base_path('../prosanatelier.com');
        if (is_dir($livePublicRoot) && is_writable($livePublicRoot)) {
            self::ensureUploadProtection($livePublicRoot);

            $relativePath = 'uploads/' . $folder . '/' . $filename;
            $targetDir = rtrim($livePublicRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $folder;
            File::ensureDirectoryExists($targetDir, 0755, true);
            self::ensureUploadProtection($targetDir);
            $file->move($targetDir, $filename);

            return $relativePath;
        }

        // Fallback for local development or hosts where the public root is not writable.
        $targetDir = Storage::disk('public')->path($folder);
        File::ensureDirectoryExists($targetDir, 0755, true);
        self::ensureUploadProtection($targetDir);
        $file->move($targetDir, $filename);

        return $folder . '/' . $filename;
    }

    public static function delete(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://', '//', 'foodmart/', 'images/'])) {
            return;
        }

        if (Str::startsWith($path, 'storage/')) {
            Storage::disk('public')->delete(Str::after($path, 'storage/'));
            return;
        }

        if (Str::startsWith($path, 'uploads/')) {
            $livePublicRoot = base_path('../prosanatelier.com');
            $livePath = rtrim($livePublicRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
            if (is_file($livePath)) {
                File::delete($livePath);
                return;
            }

            $localPublicPath = public_path($path);
            if (is_file($localPublicPath)) {
                File::delete($localPublicPath);
            }
            return;
        }

        Storage::disk('public')->delete($path);
    }

    private static function validateUpload(UploadedFile $file): void
    {
        if (! $file->isValid()) {
            throw new RuntimeException('The image could not be uploaded. Please try a smaller JPG, PNG, WebP or GIF image.');
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: '');
        if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new RuntimeException('Only JPG, PNG, WebP, GIF or SVG image files are allowed.');
        }

        $mime = strtolower((string) $file->getMimeType());
        $clientMime = strtolower((string) $file->getClientMimeType());

        if ($extension === 'svg') {
            $content = (string) @file_get_contents($file->getRealPath());
            $lower = strtolower($content);

            if (! str_contains($lower, '<svg') || str_contains($lower, '<script') || str_contains($lower, 'javascript:') || preg_match('/\son[a-z]+\s*=/i', $content)) {
                throw new RuntimeException('The SVG file contains unsafe content. Please upload a clean SVG or use PNG/JPG.');
            }

            if (! in_array($mime, ['image/svg+xml', 'text/plain', 'text/xml', 'application/xml'], true) && ! in_array($clientMime, ['image/svg+xml', 'text/plain', 'text/xml', 'application/xml'], true)) {
                throw new RuntimeException('Invalid SVG file type.');
            }

            return;
        }

        if (! in_array($mime, self::RASTER_MIME_TYPES, true) && ! in_array($clientMime, self::RASTER_MIME_TYPES, true)) {
            throw new RuntimeException('Invalid image file type. Please upload JPG, PNG, WebP or GIF.');
        }
    }

    private static function ensureUploadProtection(string $path): void
    {
        $directory = is_dir($path) ? $path : dirname($path);
        if (! is_dir($directory)) {
            return;
        }

        $htaccess = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.htaccess';
        if (is_file($htaccess)) {
            return;
        }

        @file_put_contents($htaccess, "Options -Indexes\n<FilesMatch \"\\.(php|php[0-9]?|phtml|phar|cgi|pl|py|sh|asp|aspx|jsp)$\">\n    Require all denied\n</FilesMatch>\nRemoveHandler .php .php3 .php4 .php5 .php7 .php8 .phtml .phar\nRemoveType .php .php3 .php4 .php5 .php7 .php8 .phtml .phar\n");
    }
}
