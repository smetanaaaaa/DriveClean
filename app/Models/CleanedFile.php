<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CleanedFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_name',
        'clean_name',
        'file_type',
        'original_size',
        'clean_size',
        'metadata_removed',
        'converted',
        'storage_path',
    ];

    protected $casts = [
        'metadata_removed' => 'array',
        'converted' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Форматирование размера файла
     */
    public static function formatSize(int $bytes): string
    {
        if ($bytes === 0) return '0 B';

        $units = ['B', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes, 1024));

        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }

    /**
     * Дата на русском языке
     */
    public function russianDate(): string
    {
        $months = [
            1 => 'января', 2 => 'февраля', 3 => 'марта',
            4 => 'апреля', 5 => 'мая', 6 => 'июня',
            7 => 'июля', 8 => 'августа', 9 => 'сентября',
            10 => 'октября', 11 => 'ноября', 12 => 'декабря',
        ];

        $d = $this->created_at;

        return $d->format('d') . ' ' . $months[(int) $d->format('m')] . ' ' . $d->format('Y') . ', ' . $d->format('H:i');
    }
}