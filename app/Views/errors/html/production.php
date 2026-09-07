<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Application Error') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .error { max-width: 900px; margin: 0 auto; }
        h1 { font-size: 24px; }
        pre { white-space: pre-wrap; background: #f5f5f5; padding: 16px; border-radius: 6px; }
    </style>
</head>
<body>
<div class="error">
    <h1><?= esc($title ?? 'Application Error') ?></h1>
    <p><?= esc($message ?? ($exception?->getMessage() ?? 'An error occurred.')) ?></p>
</div>
</body>
</html>
