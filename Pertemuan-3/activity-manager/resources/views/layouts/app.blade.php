<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activity Manager</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f3f4f6; color: #1f2937; margin: 0; padding: 24px; }
        .container { max-width: 680px; margin: auto; }
        .card { background: #ffffff; padding: 20px; margin-bottom: 16px; border-radius: 8px; border: 1px solid #d1d5db; }
        a { color: #2563eb; text-decoration: none; font-weight: bold; }
        .btn { display: inline-block; padding: 8px 16px; border-radius: 6px; background: #2563eb; color: #fff; border: none; cursor: pointer; text-decoration: none; }
        .btn-danger { background: #dc2626; }
        .btn-secondary { background: #6b7280; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 0.85rem; background: #e5e7eb; font-weight: bold; }
        .form-group { margin-bottom: 14px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; }
        input, textarea, select { width: 100%; padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 6px; font: inherit; }
        .error { color: #dc2626; font-size: 0.85rem; margin-top: 4px; }
        .alert-success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <main class="container">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>