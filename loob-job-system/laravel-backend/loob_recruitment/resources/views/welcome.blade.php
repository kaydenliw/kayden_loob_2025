<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'LOOB Holding') }} - Recruitment System</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Inter:300,400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(15px);
                border-radius: 20px;
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
                transition: all 0.3s ease;
            }
            .glass-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.5);
            }
            .btn-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                border-radius: 12px;
                transition: all 0.3s ease;
                font-weight: 600;
                padding: 0.75rem 2rem;
            }
            .btn-gradient:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
                background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            }
            .company-logo {
                width: 48px;
                height: 48px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 20px;
                margin-right: 16px;
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg position-fixed w-100 top-0" style="backdrop-filter: blur(15px); background: rgba(255, 255, 255, 0.1); z-index: 1000;">
            <div class="container">
                <a class="navbar-brand text-white d-flex align-items-center" href="{{ url('/') }}">
                    <div class="company-logo">L</div>
                    {{ config('app.name', 'LOOB Holding') }}
                </a>
                <div class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('recruiter.dashboard') }}" class="nav-link text-white">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="nav-link text-white">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section style="min-height: 100vh; display: flex; align-items: center; padding-top: 80px;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="text-white mb-5">
                            <div class="d-flex align-items-center mb-3">
                                <div class="company-logo me-3">L</div>
                                <div>
                                    <h4 class="mb-0">LOOB Holding</h4>
                                    <small class="opacity-75">Recruitment System</small>
                                </div>
                            </div>
                            <h1 class="display-4 fw-bold mb-4">
                                Modern Recruitment<br>
                                <span style="color: #fbbf24;">Made Simple</span>
                            </h1>
                            <p class="lead mb-4">
                                Streamline your hiring process with our cutting-edge job application system.
                            </p>
                            <div class="d-flex gap-3">
                                @auth
                                    <a href="{{ route('recruiter.dashboard') }}" class="btn btn-gradient text-white">
                                        <i class="bi bi-speedometer2 me-2"></i>Access Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-gradient text-white">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Recruiter Portal
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="glass-card p-5 text-center">
                            <div class="text-white">
                                <i class="bi bi-people-fill" style="font-size: 4rem; margin-bottom: 1.5rem; color: #fbbf24;"></i>
                                <h3 class="mb-3 fw-bold">For Job Seekers</h3>
                                <p class="mb-4">Access our mobile application to apply for positions and monitor your progress.</p>
                                <div class="row g-3">
                                    <div class="col-4 text-center">
                                        <i class="bi bi-phone" style="font-size: 2rem;"></i>
                                        <p class="small mt-2 mb-0">Mobile App<br><span class="text-warning">Coming Soon</span></p>
                                    </div>
                                    <div class="col-4 text-center">
                                        <i class="bi bi-bell" style="font-size: 2rem;"></i>
                                        <p class="small mt-2 mb-0">Real-time<br>Notifications</p>
                                    </div>
                                    <div class="col-4 text-center">
                                        <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                                        <p class="small mt-2 mb-0">Progress<br>Tracking</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer style="background: rgba(0, 0, 0, 0.2); backdrop-filter: blur(10px);" class="py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center text-white mb-2">
                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px; margin-right: 12px;">L</div>
                            <div>
                                <h6 class="mb-0">LOOB Holding</h6>
                                <small class="opacity-75">Recruitment System</small>
                            </div>
                        </div>
                        <p class="text-white mb-0 opacity-75">
                            &copy; {{ date('Y') }} LOOB Holding. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-white opacity-75">
                            <i class="bi bi-code-slash me-1"></i>
                            Powered by Laravel {{ app()->version() }}
                        </small>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
