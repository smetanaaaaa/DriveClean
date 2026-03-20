<x-main.layout>
    <div class="main-wrapper">
        <div class="card">
            <header class="header">
                <div class="logo">
                    <div class="logo-icon">
                        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="shieldGradient" x1="0%" y1="0%" x2="100%"
                                    y2="100%">
                                    <stop offset="0%" style="stop-color:#2E8BC0;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#5DC862;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path d="M50 10 L20 25 L20 50 Q20 75 50 90 Q80 75 80 50 L80 25 Z"
                                fill="url(#shieldGradient)" opacity="0.9" />
                            <ellipse cx="35" cy="40" rx="8" ry="6" fill="white" opacity="0.9" />
                            <ellipse cx="42" cy="38" rx="7" ry="7" fill="white" opacity="0.9" />
                            <ellipse cx="48" cy="40" rx="6" ry="5" fill="white" opacity="0.9" />
                            <rect x="38" y="42" width="18" height="14" rx="2" fill="white"
                                stroke="#2E8BC0" stroke-width="1.5" />
                            <circle cx="44" cy="49" r="4" fill="none" stroke="#2E8BC0" stroke-width="1.5" />
                            <line x1="44" y1="49" x2="47" y2="52" stroke="#2E8BC0" stroke-width="1.5" />
                            <line x1="40" y1="44" x2="52" y2="44" stroke="#2E8BC0" stroke-width="1" />
                            <line x1="58" y1="38" x2="68" y2="48" stroke="white" stroke-width="2.5" stroke-linecap="round" />
                            <path d="M67 48 L65 54 Q63 58 60 58 Q57 58 55 54 L53 48" fill="#5DC862" stroke="white" stroke-width="1" />
                            <circle cx="46" cy="62" r="1.5" fill="#5DC862" opacity="0.8" />
                            <circle cx="52" cy="65" r="1" fill="#5DC862" opacity="0.6" />
                            <circle cx="42" cy="66" r="1" fill="#2E8BC0" opacity="0.7" />
                            <circle cx="48" cy="68" r="1.2" fill="#5DC862" opacity="0.7" />
                        </svg>
                    </div>
                    <span class="logo-text">DriveClean</span>
                </div>
                <nav class="nav-menu">
                    <a href="/">Главная</a>
                    <a href="{{ route('how_it_works') }}">Как это работает?</a>
                    <a href="{{ route('social') }}">Социальные сети</a>
                </nav>
                <button class="btn-login" onclick="window.location.href='{{ route('login') }}'">Вход</button>
            </header>
            <main class="content content-page">
                <h1 class="page-title">Политика конфиденциальности</h1>

                <div style="max-width: 800px; margin: 0 auto; text-align: left; line-height: 1.8; color: #333;">

                    <p style="color: #666; margin-bottom: 30px;">Дата вступления в силу: 1 января 2026 года</p>

                    <h2 style="color: #1a2b4a; margin-top: 30px;">1. Введение</h2>
                    <p>Настоящая Политика конфиденциальности описывает, как DriveClean (далее — Сервис) собирает, использует и защищает персональные данные пользователей. Мы серьёзно относимся к вашей приватности — именно поэтому мы создали этот сервис.</p>

                    <h2 style="color: #1a2b4a; margin-top: 30px;">2. Какие данные мы собираем</h2>
                    <h3 style="color: #1a2b4a; margin-top: 20px;">2.1. При регистрации:</h3>
                    <ul style="padding-left: 20px;">
                        <li>Имя</li>
                        <li>Адрес электронной почты</li>
                        <li>Пароль (хранится в зашифрованном виде)</li>
                    </ul>

                    <h3 style="color: #1a2b4a; margin-top: 20px;">2.2. При использовании сервиса:</h3>
                    <ul style="padding-left: 20px;">
                        <li>Статистика обработанных файлов (количество, размер, типы удалённых метаданных)</li>
                        <li>Дата и время обработки</li>
                    </ul>

                    <h2 style="color: #1a2b4a; margin-top: 30px;">3. Какие данные мы НЕ собираем</h2>
                    <p>Это ключевой принцип работы DriveClean:</p>
                    <ul style="padding-left: 20px;">
                        <li><strong>Мы не загружаем ваши фотографии на наши серверы</strong> — обработка на главной странице происходит полностью в вашем браузере</li>
                        <li>Мы не сохраняем содержимое ваших изображений</li>
                        <li>Мы не имеем доступа к метаданным, которые удаляются из ваших файлов</li>
                        <li>Мы не передаём ваши данные третьим лицам</li>
                    </ul>

                    <h2 style="color: #1a2b4a; margin-top: 30px;">4. Как мы используем данные</h2>
                    <p>Собранные данные используются исключительно для:</p>
                    <ul style="padding-left: 20px;">
                        <li>Обеспечения работы личного кабинета</li>
                        <li>Ведения истории обработанных файлов</li>
                        <li>Формирования статистики использования Сервиса</li>
                        <li>Улучшения качества работы Сервиса</li>
                    </ul>

                    <h2 style="color: #1a2b4a; margin-top: 30px;">5. Защита данных</h2>
                    <p>Мы применяем следующие меры безопасности:</p>
                    <ul style="padding-left: 20px;">
                        <li>Шифрование паролей с использованием bcrypt</li>
                        <li>Защита от CSRF-атак</li>
                        <li>HTTPS-соединение для передачи данных</li>
                        <li>Регулярное обновление программного обеспечения</li>
                    </ul>

                    <h2 style="color: #1a2b4a; margin-top: 30px;">6. Файлы cookie</h2>
                    <p>Сервис использует только технические cookie, необходимые для работы авторизации и защиты сессий. Мы не используем рекламные или аналитические cookie.</p>

                    <h2 style="color: #1a2b4a; margin-top: 30px;">7. Права пользователя</h2>
                    <p>Вы имеете право:</p>
                    <ul style="padding-left: 20px;">
                        <li>Запросить информацию о хранящихся персональных данных</li>
                        <li>Потребовать удаления вашего аккаунта и всех связанных данных</li>
                        <li>Отказаться от использования Сервиса в любое время</li>
                    </ul>

                    <h2 style="color: #1a2b4a; margin-top: 30px;">8. Изменение политики</h2>
                    <p>Мы можем обновлять настоящую Политику. О существенных изменениях пользователи будут уведомлены через Сервис или по электронной почте.</p>

                    <h2 style="color: #1a2b4a; margin-top: 30px;">9. Контакты</h2>
                    <p>По вопросам конфиденциальности обращайтесь через Telegram: <a href="https://t.me/driveclean1" style="color: #2E8BC0;">@driveclean1</a></p>

                </div>
            </main>
        </div>
        <div class="footer-timestamp">
            &copy; 2025 DriveClean
        </div>
    </div>
</x-main.layout>
