# Flexinform - Laravel + Vue autó szerviznapló

Ez az alkalmazás egy egyszerű rendszer, amivel ügyfeleket, azok autóit és a hozzájuk tartozó szerviznaplókat lehet nyilvántartani. Laravel-t használ a backendhez és Vue-t a frontendhez. A keresés név vagy okmányazonosító alapján történik, és a megjelenítés nem frissíti újra az oldalt.

## Használt verziók

- PHP: 8.3
- Laravel: 12.7.2
- Laravel Herd: 1.19.0
- Vue.js: 3.5.13
- Vite: 6.2.5
- Axios: 1.8.4
- Tailwind CSS: 4.1.3
- @vitejs/plugin-vue: 5.2.3
- @tailwindcss/vite: 4.1.3
- laravel-vite-plugin: 1.2.0

## Telepítés és beállítás

1. Klónozd a repót vagy másold a projektet.
2. Futtasd az alábbi parancsokat:

```
composer install
npm install
```

3. Készíts egy `.env` fájlt az `.env.example` alapján:

```
cp .env.example .env
```

4. Generálj kulcsot:

```
php artisan key:generate
```

5. Beállítás az `.env` fájlban:

```
APP_URL=https://flexinform-feladat.test
DB_CONNECTION=sqlite
DB_DATABASE=/full/path/to/database.sqlite
```

6. Hozz létre egy üres adatbázist:

```
touch database/database.sqlite
```

7. Indítsd el a Vite szervert (indítsd el a Laravel Herd-et is):

```
npm run dev
```

8. Nyisd meg böngészőben:

```
https://flexinform-feladat.test
```

(Ha nem működik, nézd meg, hogy a Vite fut-e, és nincs-e portütközés.)

## Fontosabb komponensek

- `ClientSearch.vue`: keresés ügyfél név vagy azonosító alapján
- `ClientList.vue`: ügyfelek listája, lenyíló autókkal és szerviznaplóval
- `CarList.vue`: autók listája (külön komponensként is használható)
- `ServiceLog.vue`: egy autóhoz tartozó szerviznaplók megjelenítése

## Adatfeltöltés

A rendszer automatikusan betölti a csatolt JSON fájlokból az adatokat, ha az adatbázis üres.

## Megjegyzések

A fejlesztés Windows alatt, Laravel Herd segítségével történt. Az SSL és Vite konfigurációk kézzel lettek beállítva, ahogy szükséges volt. A projekt célja a Laravel + Vue gyakorlása volt, éles környezetbe nem javasolt.

---

Készítette:
Perger Tamás
