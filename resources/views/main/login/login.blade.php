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
                            <ellipse cx="35" cy="40" rx="8" ry="6" fill="white"
                                opacity="0.9" />
                            <ellipse cx="42" cy="38" rx="7" ry="7" fill="white"
                                opacity="0.9" />
                            <ellipse cx="48" cy="40" rx="6" ry="5" fill="white"
                                opacity="0.9" />
                            <rect x="38" y="42" width="18" height="14" rx="2" fill="white"
                                stroke="#2E8BC0" stroke-width="1.5" />
                            <circle cx="44" cy="49" r="4" fill="none" stroke="#2E8BC0"
                                stroke-width="1.5" />
                            <line x1="44" y1="49" x2="47" y2="52" stroke="#2E8BC0"
                                stroke-width="1.5" />
                            <line x1="40" y1="44" x2="52" y2="44" stroke="#2E8BC0"
                                stroke-width="1" />
                            <line x1="58" y1="38" x2="68" y2="48" stroke="white"
                                stroke-width="2.5" stroke-linecap="round" />
                            <path d="M67 48 L65 54 Q63 58 60 58 Q57 58 55 54 L53 48" fill="#5DC862" stroke="white"
                                stroke-width="1" />
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
                <button class="btn-login" onclick="window.location.href='{{ route('register') }}'">Регистрация</button>
            </header>
            <main class="content content-page">
                <h1 class="page-title">Вход в аккаунт</h1>

                <div class="auth-container single">
                    <div class="auth-card">
                        <form method="POST" action="{{ route('login.store') }}" class="auth-form">
                            @csrf

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required
                                    placeholder="example@mail.com" value="{{ old('email') }}">
                                @error('email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Пароль</label>
                                <input type="password" id="password" name="password" required
                                    placeholder="Введите пароль">
                            </div>

                            <div class="form-group checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="remember">
                                    <span>Запомнить меня</span>
                                </label>
                                <a href="#" class="forgot-link">Забыли пароль?</a>
                            </div>

                            <button type="submit" class="btn-submit">Войти</button>
                        </form>

                        <div class="auth-footer">
                            <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="footer-timestamp">
            © 2025 DriveClean
        </div>
    </div>
</x-main.layout>
