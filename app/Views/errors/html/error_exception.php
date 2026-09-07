<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Error') ?></title>
</head>
<body>
    <h1><?= esc($title ?? 'Error') ?></h1>
    <p><?= esc($message ?? ($exception?->getMessage() ?? 'An error occurred.')) ?></p>
</body>
</html>
