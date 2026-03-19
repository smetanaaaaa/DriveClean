<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveClean — Админ-панель</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            min-height: 100vh;
        }

        /* Header */
        .admin-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-header h1 {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .admin-header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .admin-header a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .admin-header a:hover {
            color: white;
        }

        /* Container */
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Stats grid */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .stat-card .stat-label {
            font-size: 0.8rem;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a2e;
        }

        .stat-card .stat-sub {
            font-size: 0.8rem;
            color: #aaa;
            margin-top: 4px;
        }

        .stat-card.highlight {
            background: linear-gradient(135deg, #2E8BC0, #5DC862);
            color: white;
        }

        .stat-card.highlight .stat-label {
            color: rgba(255, 255, 255, 0.8);
        }

        .stat-card.highlight .stat-value {
            color: white;
        }

        .stat-card.highlight .stat-sub {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Sections */
        .section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .section h2 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: #1a1a2e;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            padding: 8px 12px;
            border-bottom: 2px solid #f0f2f5;
        }

        td {
            padding: 10px 12px;
            border-bottom: 1px solid #f5f5f5;
            font-size: 0.9rem;
        }

        tr:hover td {
            background: #fafbfc;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-admin {
            background: #fff3cd;
            color: #856404;
        }

        .badge-user {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .text-muted {
            color: #aaa;
        }

        .text-sm {
            font-size: 0.8rem;
        }

        /* Two columns */
        .two-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        @media (max-width: 768px) {
            .two-cols {
                grid-template-columns: 1fr;
            }

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <div class="admin-header">
        <h1>🛡️ DriveClean — Админ-панель</h1>
        <div class="admin-header-right">
            <a href="{{ route('home') }}">← На сайт</a>
            <a href="{{ route('dashboard') }}">Личный кабинет</a>
        </div>
    </div>

    <div class="admin-container">

        {{-- Статистика: пользователи --}}
        <div class="stats-row">
            <div class="stat-card highlight">
                <div class="stat-label">Всего пользователей</div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-sub">+{{ $todayUsers }} сегодня</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">За неделю</div>
                <div class="stat-value">{{ $weekUsers }}</div>
                <div class="stat-sub">новых пользователей</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">За месяц</div>
                <div class="stat-value">{{ $monthUsers }}</div>
                <div class="stat-sub">новых пользователей</div>
            </div>
        </div>

        {{-- Статистика: файлы --}}
        <div class="stats-row">
            <div class="stat-card highlight">
                <div class="stat-label">Всего очищено файлов</div>
                <div class="stat-value">{{ number_format($totalFiles) }}</div>
                <div class="stat-sub">+{{ $todayFiles }} сегодня, +{{ $weekFiles }} за неделю</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Загружено данных</div>
                <div class="stat-value">{{ \App\Models\CleanedFile::formatSize($totalOriginalSize) }}</div>
                <div class="stat-sub">оригинальный размер</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">После очистки</div>
                <div class="stat-value">{{ \App\Models\CleanedFile::formatSize($totalCleanSize) }}</div>
                <div class="stat-sub">очищенный размер</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Экономия</div>
                <div class="stat-value">{{ \App\Models\CleanedFile::formatSize($totalSaved) }}</div>
                <div class="stat-sub">
                    @if ($totalOriginalSize > 0)
                        {{ round(($totalSaved / $totalOriginalSize) * 100, 1) }}% сжатие
                    @else
                        0%
                    @endif
                </div>
            </div>
        </div>

        {{-- Две колонки --}}
        <div class="two-cols">
            {{-- Топ пользователей --}}
            <div class="section">
                <h2>🏆 Топ пользователей</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Файлов</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topUsers as $user)
                            <tr>
                                <td>
                                    {{ $user->name }}
                                    @if ($user->is_admin)
                                        <span class="badge badge-admin">Admin</span>
                                    @endif
                                </td>
                                <td class="text-muted text-sm">{{ $user->email }}</td>
                                <td><strong>{{ $user->cleaned_files_count }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted">Нет данных</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Последние регистрации --}}
            <div class="section">
                <h2>👤 Последние регистрации</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentUsers as $user)
                            @php
                                $months = [
                                    1 => 'янв',
                                    2 => 'фев',
                                    3 => 'мар',
                                    4 => 'апр',
                                    5 => 'мая',
                                    6 => 'июн',
                                    7 => 'июл',
                                    8 => 'авг',
                                    9 => 'сен',
                                    10 => 'окт',
                                    11 => 'ноя',
                                    12 => 'дек',
                                ];
                                $d = $user->created_at;
                                $dateStr =
                                    $d->format('d') . ' ' . $months[(int) $d->format('m')] . ' ' . $d->format('Y, H:i');
                            @endphp
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td class="text-muted text-sm">{{ $user->email }}</td>
                                <td class="text-sm">{{ $dateStr }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted">Нет данных</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Последние файлы --}}
        <div class="section">
            <h2>📁 Последние очищенные файлы</h2>
            <table>
                <thead>
                    <tr>
                        <th>Файл</th>
                        <th>Пользователь</th>
                        <th>Размер</th>
                        <th>Метаданные</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentFiles as $file)
                        @php
                            $months = [
                                1 => 'янв',
                                2 => 'фев',
                                3 => 'мар',
                                4 => 'апр',
                                5 => 'мая',
                                6 => 'июн',
                                7 => 'июл',
                                8 => 'авг',
                                9 => 'сен',
                                10 => 'окт',
                                11 => 'ноя',
                                12 => 'дек',
                            ];
                            $d = $file->created_at;
                            $dateStr =
                                $d->format('d') . ' ' . $months[(int) $d->format('m')] . ', ' . $d->format('H:i');
                        @endphp
                        <tr>
                            <td>
                                {{ Str::limit($file->original_name, 30) }}
                            </td>
                            <td class="text-sm">{{ $file->user->name ?? '—' }}</td>
                            <td class="text-sm">
                                {{ \App\Models\CleanedFile::formatSize($file->original_size) }}
                                → {{ \App\Models\CleanedFile::formatSize($file->clean_size) }}
                            </td>
                            <td class="text-sm text-muted">
                                @if ($file->metadata_removed)
                                    {{ implode(', ', $file->metadata_removed) }}
                                @endif
                            </td>
                            <td class="text-sm">{{ $dateStr }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">Нет данных</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>
