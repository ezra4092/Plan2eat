<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class MediaStorageService
{
    /**
     * Mengunggah file gambar ke Supabase Storage jika terkonfigurasi,
     * atau ke penyimpanan lokal (public/menu atau storage public) saat development.
     */
    public function upload(UploadedFile $file, string $folder, ?string $localPublicDir = null): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $prefix = str_replace(['/', '-', ' '], '_', trim($folder, '/'));
        $filename = $prefix . '_' . now()->format('Ymd_His') . '_' . Str::random(8) . '.' . $extension;

        if ($this->isSupabaseConfigured()) {
            return $this->uploadToSupabase($file, trim($folder, '/'), $filename);
        }

        if ($this->isReadOnlyServerless()) {
            throw new RuntimeException('Penyimpanan lokal tidak tersedia di Vercel. Harap atur SUPABASE_URL dan SUPABASE_SERVICE_ROLE_KEY pada Environment Variables.');
        }

        if ($localPublicDir !== null && $localPublicDir !== '') {
            $targetDir = public_path($localPublicDir);

            try {
                File::ensureDirectoryExists($targetDir, 0775, true);
                $contents = file_get_contents($file->getRealPath());

                if ($contents !== false && File::put($targetDir . DIRECTORY_SEPARATOR . $filename, $contents) !== false) {
                    return trim($localPublicDir, '/') . '/' . $filename;
                }
            } catch (\Throwable $e) {
                // Fallback ke disk public Laravel jika folder public terkunci OS/OneDrive
            }
        }

        return Storage::disk('public')->putFileAs(trim($folder, '/'), $file, $filename);
    }

    /**
     * Menghapus file gambar lama dari Supabase Storage atau penyimpanan lokal bila tersedia.
     */
    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        if ($this->isSupabaseConfigured() && $this->isSupabasePublicUrl($path)) {
            $this->deleteFromSupabase($path);

            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        $publicFile = public_path($path);
        if (File::exists($publicFile)) {
            File::delete($publicFile);
        } elseif (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Mengubah path/URL tersimpan menjadi URL publik yang valid.
     */
    public static function resolveUrl(?string $path, ?string $fallback = null): ?string
    {
        if (! $path) {
            return $fallback;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'menu/') || file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    private function isSupabaseConfigured(): bool
    {
        $url = trim((string) config('services.supabase.url'));
        $key = trim((string) config('services.supabase.service_role_key'));

        return $url !== '' && $key !== '';
    }

    private function isReadOnlyServerless(): bool
    {
        return ! empty($_ENV['VERCEL']) || ! empty($_SERVER['VERCEL']) || getenv('VERCEL') !== false;
    }

    private function isSupabasePublicUrl(string $path): bool
    {
        $baseUrl = rtrim((string) config('services.supabase.url'), '/');
        $bucket = trim((string) config('services.supabase.bucket', 'plan2eat'), '/');

        return $baseUrl !== '' && str_starts_with($path, "{$baseUrl}/storage/v1/object/public/{$bucket}/");
    }

    private function uploadToSupabase(UploadedFile $file, string $folder, string $filename): string
    {
        $baseUrl = rtrim((string) config('services.supabase.url'), '/');
        $serviceKey = trim((string) config('services.supabase.service_role_key'));
        $bucket = trim((string) config('services.supabase.bucket', 'plan2eat'), '/');
        $objectPath = ($folder !== '' ? $folder . '/' : '') . $filename;

        $contents = file_get_contents($file->getRealPath());
        if ($contents === false) {
            throw new RuntimeException('Gagal membaca file gambar yang diunggah.');
        }

        $mimeType = $file->getMimeType() ?: 'image/jpeg';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $serviceKey,
            'apikey' => $serviceKey,
            'x-upsert' => 'true',
        ])
            ->withBody($contents, $mimeType)
            ->post("{$baseUrl}/storage/v1/object/{$bucket}/{$objectPath}");

        if ($response->failed()) {
            throw new RuntimeException('Gagal mengunggah gambar ke Supabase Storage: ' . $response->body());
        }

        return "{$baseUrl}/storage/v1/object/public/{$bucket}/{$objectPath}";
    }

    private function deleteFromSupabase(string $publicUrl): void
    {
        $baseUrl = rtrim((string) config('services.supabase.url'), '/');
        $serviceKey = trim((string) config('services.supabase.service_role_key'));
        $bucket = trim((string) config('services.supabase.bucket', 'plan2eat'), '/');
        $prefix = "{$baseUrl}/storage/v1/object/public/{$bucket}/";
        $objectPath = substr($publicUrl, strlen($prefix));

        if ($objectPath === '' || $objectPath === false) {
            return;
        }

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $serviceKey,
            'apikey' => $serviceKey,
        ])->delete("{$baseUrl}/storage/v1/object/{$bucket}/{$objectPath}");
    }
}
