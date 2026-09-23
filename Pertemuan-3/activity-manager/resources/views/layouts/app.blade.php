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
        a:hover { text-decoration: underline; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 0.85rem; background: #e5e7eb; font-weight: bold; }
    </style>
</head>
<body>
    <main class="container">
        @yield('content')
    </main>
</body>
</html>