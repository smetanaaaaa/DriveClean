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
            'files.*' => 'required|file|max:20480',
        ], [
            'files.required' => 'Выберите файлы для очистки.',
            'files.*.max' => 'Максимальный размер файла — 20 MB.',
        ]);

        $user = Auth::user();
        $results = [];
        $originalNames = $request->input('original_names', []);
        $originalSizes = $request->input('original_sizes', []);
        $cleanSizes = $request->input('clean_sizes', []);

        foreach ($request->file('files') as $i => $file) {
            $originalName = $originalNames[$i] ?? $file->getClientOriginalName();
            $fileType = $file->getMimeType();
            $originalSize = (int) ($originalSizes[$i] ?? $file->getSize());
            $cleanSize = (int) ($cleanSizes[$i] ?? $file->getSize());
            $cleanName = $file->getClientOriginalName();
            $metadataRemoved = ['EXIF', 'GPS', 'IPTC', 'Thumbnail', 'Color Profile'];

            $path = $file->storeAs(
                'cleaned/' . $user->id,
                $cleanName,
                'public'
            );

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

    public function destroy(CleanedFile $file)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        // Удаляем файл из хранилища
        if ($file->storage_path && Storage::disk('public')->exists($file->storage_path)) {
            Storage::disk('public')->delete($file->storage_path);
        }

        $file->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Файл удалён.');
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