<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Flexinform - Szerviznapló</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal min-h-screen flex items-center justify-center">

    <div id="app" class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <client-search></client-search>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <client-list></client-list>
        </div>
    </div>

</body>
</html>
