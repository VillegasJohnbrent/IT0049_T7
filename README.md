# TFA7 Laravel POS

John Brent D. Villegas · TX33 · IT0049

Customer and user create/edit forms, input validation and user avatars. Built on TFA6 using Laravel 12.

## Setup

Use PHP 8.2+, Composer 2, GD, Mbstring, Fileinfo and PDO SQLite. Enable GD in php.ini for avatar processing. Set upload_max_filesize to at least 2M and post_max_size to 8M.

Open CMD in this folder:

```cmd
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Open http://127.0.0.1:8000. The ZIP includes an empty database/database.sqlite file. After a GitHub clone, create it before migrating:

```cmd
if not exist database\database.sqlite type nul > database\database.sqlite
```

For an existing TFA6 installation, back up the database, keep its .env and run composer install, php artisan migrate and php artisan storage:link. Do not reseed an existing database unless you want to reset the sample records.

## Avatars

Edit a user to upload a JPG, PNG, WEBP or GIF up to 2 MB and 4096 by 4096 pixels. Intervention Image 3 with GD saves a 160 by 160 JPEG on the public disk. Confirm this package with the instructor. The database stores its relative path. A replacement removes the old image after the record saves.

## Checks

```cmd
composer test
php artisan route:list
```

The tests cover forms, validation, old input, unique usernames, thumbnails and file replacement. They have not been run in the preparation environment because PHP and Composer were unavailable.

## Hosting

Use PHP hosting with Composer and the extensions listed above. Set the document root to public/. Keep storage/ and bootstrap/cache/ writable. Use persistent storage for the SQLite database and uploaded images.

Set APP_ENV=production, APP_DEBUG=false and APP_URL to the real HTTPS address. Set DB_DATABASE to the absolute SQLite path, or configure the host's MySQL credentials.

On the server, install dependencies with composer install --no-dev --optimize-autoloader. Generate an application key once, run php artisan migrate --seed --force on the new database, then php artisan storage:link and php artisan config:cache. Check the four pages, both create forms, both edit forms and an avatar upload.

Repository: https://github.com/VillegasJohnbrent/IT0049_T7

Submit the deployed application URL separately. No hosted URL has been configured.
