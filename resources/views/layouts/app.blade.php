<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sensor-bg: radial-gradient(circle at top left, #12304d 0, #0f172a 45%, #020617 100%);
            --sensor-card: rgba(15, 23, 42, 0.74);
            --sensor-border: rgba(255, 255, 255, 0.08);
            --sensor-text: #e2e8f0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--sensor-bg);
            color: var(--sensor-text);
            min-height: 100vh;
        }

        .sensor-shell {
            position: relative;
            overflow: hidden;
        }

        .sensor-shell::before,
        .sensor-shell::after {
            content: '';
            position: fixed;
            width: 26rem;
            height: 26rem;
            border-radius: 999px;
            filter: blur(24px);
            opacity: 0.35;
            pointer-events: none;
            z-index: 0;
        }

        .sensor-shell::before {
            top: -8rem;
            right: -8rem;
            background: #38bdf8;
        }

        .sensor-shell::after {
            bottom: -10rem;
            left: -10rem;
            background: #f97316;
        }

        .glass-card {
            background: var(--sensor-card);
            border: 1px solid var(--sensor-border);
            box-shadow: 0 24px 80px rgba(2, 6, 23, 0.35);
            border-radius: 1.25rem;
            position: relative;
            z-index: 1;
        }

        .brand-mark {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #38bdf8, #f97316);
            color: white;
            font-weight: 700;
        }

        .text-soft {
            color: rgba(226, 232, 240, 0.75);
        }

        .table-darkish {
            --bs-table-bg: rgba(15, 23, 42, 0.5);
            --bs-table-striped-bg: rgba(30, 41, 59, 0.45);
            --bs-table-color: #e2e8f0;
            --bs-table-border-color: rgba(255,255,255,.08);
        }

        .btn-ghost {
            border: 1px solid rgba(255,255,255,.15);
            color: #e2e8f0;
            background: rgba(255,255,255,.04);
        }

        .btn-ghost:hover {
            background: rgba(255,255,255,.12);
            color: #fff;
        }
    </style>
    @stack('head')
</head>
<body class="sensor-shell">
<nav class="navbar navbar-expand-lg navbar-dark border-bottom border-white border-opacity-10 bg-black bg-opacity-25 sticky-top position-relative" style="z-index:2;">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-semibold" href="{{ route('home') }}">
            <span class="brand-mark">S</span>
            <span>SensorWatch</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sensorNav" aria-controls="sensorNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="sensorNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-1">
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('sensores.show', 'suelo') }}">Suelo</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('sensores.show', 'ambiente') }}">Ambiente</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('sensores.show', 'clima') }}">Clima</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('predicciones.index') }}">Predicciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('umbrales.index') }}">Umbrales</a></li>
                @endauth
            </ul>
            <div class="d-flex align-items-center gap-2">
                @guest
                    <a class="btn btn-sm btn-ghost" href="{{ route('login') }}">Entrar</a>
                    <a class="btn btn-sm btn-warning" href="{{ route('register') }}">Crear cuenta</a>
                @endguest

                @auth
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell me-1"></i>
                            <span class="badge text-bg-danger ms-1" id="notificationCount">0</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark p-2" id="notificationDropdown" style="min-width: 22rem; max-height: 24rem; overflow:auto;"></ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->nombre }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                            <li><a class="dropdown-item" href="{{ route('password.change') }}">Cambiar contraseña</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">Cerrar sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="container py-5 position-relative" style="z-index:1;">
    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@auth
<script>
    const notificationEndpoint = @json(route('notificaciones.index'));
    const deleteTemplate = @json(route('notificaciones.destroy', ['notification' => '__ID__']));

    async function loadNotifications() {
        const response = await fetch(notificationEndpoint, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();
        const countElement = document.getElementById('notificationCount');
        const dropdown = document.getElementById('notificationDropdown');

        countElement.textContent = data.num_notificaciones;
        dropdown.innerHTML = '';

        if (!data.notificaciones.length) {
            dropdown.innerHTML = '<li><span class="dropdown-item-text text-soft">No hay notificaciones</span></li>';
            return;
        }

        data.notificaciones.forEach((notification) => {
            const item = document.createElement('li');
            item.innerHTML = `
                <div class="dropdown-item-text mb-2">
                    <div class="small text-soft mb-1">${notification.fecha ?? ''}</div>
                    <div class="d-flex justify-content-between gap-2 align-items-start">
                        <span class="small">${notification.mensaje ?? ''}</span>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" type="button" data-id="${notification.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            dropdown.appendChild(item);
        });

        dropdown.querySelectorAll('button[data-id]').forEach((button) => {
            button.addEventListener('click', async () => {
                const url = deleteTemplate.replace('__ID__', button.dataset.id);
                await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                loadNotifications();
            });
        });
    }

    loadNotifications();
</script>
@endauth
@stack('scripts')
</body>
</html>