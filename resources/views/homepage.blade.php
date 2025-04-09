<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Flexinform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div id="app" class="w-full max-w-3xl space-y-6 p-4">
        <div>
            <client-search></client-search>
        </div>

        <div>
            <client-list></client-list>
        </div>
    </div>

</body>
</html>
