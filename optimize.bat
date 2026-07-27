@echo off
echo Mengoptimalkan J^&J Sentral untuk Production...

echo Membersihkan seluruh cache...
php artisan optimize:clear

echo Membuat cache konfigurasi...
php artisan config:cache

echo Membuat cache routing...
php artisan route:cache

echo Membuat cache view Blade...
php artisan view:cache

echo Selesai! Aplikasi J^&J Sentral telah dioptimasi dan siap digunakan.
pause
