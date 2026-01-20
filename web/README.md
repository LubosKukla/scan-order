# Instalacia a spustenie

## 1) XAMPP

- Zapni 3 sluzby v XAMPP (Apache, MySQL, ProFTPD).

## 2) Backend (Laravel)

```
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Uprav DB nastavenia v `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD), potom:

```
php artisan migrate --seed
php artisan serve --host=localhost --port=8000
```

Backend bezi na `http://localhost:8000`.

## 3) Frontend (Vue)

```
cd web
npm install
npm run serve
```

Frontend bezi na `http://localhost:8080` a v prehliadaci sa tam da prihlasit na web.
