<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Autószerviz rendszer</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app">
            <button @click="toggleView" style="margin-bottom: 1rem;">
                @{{ showList ? 'Keresés' : 'Vissza az Ügyfelek →' }}
            </button>


            <client-search v-if="!showList"></client-search>
            <client-list v-if="showList"></client-list>
        </div>
    </body>
</html>
