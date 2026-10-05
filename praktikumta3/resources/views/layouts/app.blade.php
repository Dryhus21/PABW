<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f6f8;
        }
        header {
            background-color: #1e40af;
            color: white;
            padding: 20px;
        }
        header h1 {
            margin: 0;
        }
        nav {
            margin-top: 10px;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }
        main {
            padding: 20px;
        }
        footer {
            text-align: center;
            padding: 15px;
            background-color: #1e40af;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>LaporBanjir</h1>
        <p>Sistem Pelaporan Banjir BPBD Kabupaten Bandung</p>
        <nav>
            <a href="{{ route('laporan.create') }}">Form Pelaporan</a>
            <a href="{{ route('laporan.index') }}">Daftar Laporan</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        &copy; 2026 LaporBanjir - BPBD Kabupaten Bandung
    </footer>
</body>
</html>
