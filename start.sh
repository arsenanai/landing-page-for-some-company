$IP = ipconfig getifaddr en1;

sudo php artisan serve --host $IP --port 9000;