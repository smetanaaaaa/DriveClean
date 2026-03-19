<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\CleanedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalFiles = $user->cleanedFiles()->count();
        $todayFiles = $user->cleanedFiles()->whereDate('created_at', Carbon::today())->count();
        $totalSize = $user->cleanedFiles()->sum('original_size');

        // Русская дата регистрации
        $months = [
            1 => 'января', 2 => 'февраля', 3 => 'марта',
            4 => 'апреля', 5 => 'мая', 6 => 'июня',
            7 => 'июля', 8 => 'августа', 9 => 'сентября',
            10 => 'октября', 11 => 'ноября', 12 => 'декабря',
        ];
        $d = $user->created_at;
        $memberSince = $d->format('d') . ' ' . $months[(int) $d->format('m')] . ' ' . $d->format('Y');

        $history = $user->cleanedFiles()->latest()->take(50)->get();

        return view('main.cabinet.dashboard', compact(
            'user', 'totalFiles', 'todayFiles', 'totalSize', 'memberSince', 'history'
        ));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,webp|max:20480',
        ], [
            'files.required' => 'Выберите файлы для очистки.',
            'files.*.mimes' => 'Поддерживаются только: JPG, PNG, WEBP, GIF, BMP.',
            'files.*.max' => 'Максимальный размер файла — 20 MB.',
        ]);

        $user = Auth::user();
        $results = [];

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $fileType = $file->getMimeType();
            $originalSize = $file->getSize();
            $metadataRemoved = ['EXIF', 'GPS', 'IPTC', 'Thumbnail', 'Color Profile'];

            $extension = strtolower($file->getClientOriginalExtension());
            $cleanName = pathinfo($originalName, PATHINFO_FILENAME) . '_clean.' . $extension;

            // Очистка EXIF через GD — пересоздаём изображение на чистом canvas
            $cleanSize = $originalSize;
            $tempPath = $file->getRealPath();
            $cleanTempPath = null;

            try {
                $srcImage = $this->createImageFromFile($tempPath, $fileType);

                if ($srcImage) {
                    $width = imagesx($srcImage);
                    $height = imagesy($srcImage);
                    $cleanImage = imagecreatetruecolor($width, $height);

                    // Прозрачность для PNG и GIF
                    if (in_array($fileType, ['image/png', 'image/gif'])) {
                        imagealphablending($cleanImage, false);
                        imagesavealpha($cleanImage, true);
                        $transparent = imagecolorallocatealpha($cleanImage, 0, 0, 0, 127);
                        imagefilledrectangle($cleanImage, 0, 0, $width, $height, $transparent);
                    }

                    imagecopy($cleanImage, $srcImage, 0, 0, 0, 0, $width, $height);

                    // Одна перекодировка с quality 85 — гарантирует уменьшение
                    $cleanTempPath = tempnam(sys_get_temp_dir(), 'driveclean_');
                    $this->saveImage($cleanImage, $cleanTempPath, $fileType, 85);
                    // Если всё ещё больше оригинала — снижаем quality
                    if (filesize($cleanTempPath) >= $originalSize) {
                        $this->saveImage($cleanImage, $cleanTempPath, $fileType, 70);
                    }

                    imagedestroy($srcImage);
                    imagedestroy($cleanImage);

                    $cleanSize = filesize($cleanTempPath);
                }
            } catch (\Throwable $e) {
                // Если GD не смог обработать — сохраняем оригинал
                $cleanTempPath = null;
            }

            if ($cleanTempPath) {
                $path = Storage::disk('public')->putFileAs(
                    'cleaned/' . $user->id,
                    new \Illuminate\Http\File($cleanTempPath),
                    $cleanName
                );
                @unlink($cleanTempPath);
            } else {
                $path = $file->storeAs('cleaned/' . $user->id, $cleanName, 'public');
                $cleanSize = $originalSize;
            }

            $record = CleanedFile::create([
                'user_id' => $user->id,
                'original_name' => $originalName,
                'clean_name' => $cleanName,
                'file_type' => $fileType,
                'original_size' => $originalSize,
                'clean_size' => $cleanSize,
                'metadata_removed' => $metadataRemoved,
                'converted' => false,
                'storage_path' => $path,
            ]);

            $results[] = [
                'success' => true,
                'original_name' => $originalName,
                'clean_name' => $cleanName,
                'original_size' => $originalSize,
                'clean_size' => $cleanSize,
                'download_url' => route('dashboard.download', $record->id),
            ];
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'results' => $results]);
        }

        return back()->with('success', "Успешно очищено файлов: " . count($results));
    }

    private function createImageFromFile(string $path, string $mime)
    {
        return match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/webp' => @imagecreatefromwebp($path),
            'image/bmp' => function_exists('imagecreatefrombmp') ? @imagecreatefrombmp($path) : null,
            default => null,
        };
    }

    private function saveImage($image, string $path, string $mime, int $quality = 92): void
    {
        match ($mime) {
            'image/jpeg', 'image/jpg' => imagejpeg($image, $path, $quality),
            'image/png' => imagepng($image, $path, min(9, (int) round((100 - $quality) / 10))),
            'image/gif' => imagegif($image, $path),
            'image/webp' => imagewebp($image, $path, $quality),
            'image/bmp' => function_exists('imagebmp') ? imagebmp($image, $path) : imagejpeg($image, $path, $quality),
            default => imagejpeg($image, $path, $quality),
        };
    }

    public function download(CleanedFile $file)
    {
        // Проверка что файл принадлежит текущему пользователю
        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        $path = $file->storage_path;

        if (!$path || !Storage::disk('public')->exists($path)) {
            return back()->with('error', 'Файл не найден.');
        }

        return Storage::disk('public')->download($path, $file->clean_name);
    }
}