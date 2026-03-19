<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\CleanedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CleanController extends Controller
{
    /**
     * Сохранение результата клиентской очистки на сервер.
     * Вызывается через fetch из JS после очистки EXIF на клиенте.
     */
    public function store(Request $request)
    {
        $request->validate([
            'original_name' => 'required|string|max:255',
            'clean_name' => 'required|string|max:255',
            'file_type' => 'nullable|string|max:50',
            'original_size' => 'required|integer|min:0',
            'clean_size' => 'required|integer|min:0',
            'metadata_removed' => 'nullable|string',
            'converted' => 'nullable|in:0,1',
            'clean_file' => 'nullable|file|max:20480',
        ]);

        $user = Auth::user();
        $storagePath = null;

        // Сохраняем очищенный файл если передан
        if ($request->hasFile('clean_file')) {
            $storagePath = $request->file('clean_file')->storeAs(
                'cleaned/' . $user->id,
                $request->clean_name,
                'public'
            );
        }

        CleanedFile::create([
            'user_id' => $user->id,
            'original_name' => $request->original_name,
            'clean_name' => $request->clean_name,
            'file_type' => $request->file_type,
            'original_size' => $request->original_size,
            'clean_size' => $request->clean_size,
            'metadata_removed' => json_decode($request->metadata_removed, true) ?? [],
            'converted' => (bool) $request->converted,
            'storage_path' => $storagePath,
        ]);

        return response()->json(['success' => true]);
    }
}