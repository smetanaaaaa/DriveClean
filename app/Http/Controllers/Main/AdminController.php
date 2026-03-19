<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\CleanedFile;
use App\Models\User;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Пользователи
        $totalUsers = User::count();
        $todayUsers = User::whereDate('created_at', Carbon::today())->count();
        $weekUsers = User::where('created_at', '>=', Carbon::now()->subWeek())->count();
        $monthUsers = User::where('created_at', '>=', Carbon::now()->subMonth())->count();

        // Файлы
        $totalFiles = CleanedFile::count();
        $todayFiles = CleanedFile::whereDate('created_at', Carbon::today())->count();
        $weekFiles = CleanedFile::where('created_at', '>=', Carbon::now()->subWeek())->count();

        // Размеры
        $totalOriginalSize = CleanedFile::sum('original_size');
        $totalCleanSize = CleanedFile::sum('clean_size');
        $totalSaved = $totalOriginalSize - $totalCleanSize;

        // Топ пользователей по количеству файлов
        $topUsers = User::withCount('cleanedFiles')
            ->orderByDesc('cleaned_files_count')
            ->take(10)
            ->get();

        // Последние зарегистрированные
        $recentUsers = User::latest()->take(10)->get();

        // Последние очищенные файлы
        $recentFiles = CleanedFile::with('user')->latest()->take(20)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'todayUsers', 'weekUsers', 'monthUsers',
            'totalFiles', 'todayFiles', 'weekFiles',
            'totalOriginalSize', 'totalCleanSize', 'totalSaved',
            'topUsers', 'recentUsers', 'recentFiles'
        ));
    }
}