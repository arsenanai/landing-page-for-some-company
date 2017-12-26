Web site for cybersec.kz

Software requirements:
composer(v1.3.2), 
php(v7.1.4(used), v5.6(min for laravel)), 
mysql(v5.7 min for JSON datatype) - database schema named "cybersec" with collation "utf8_unicode_ci", 
laravel(v5.4.18, installer v1.3.5)

to install make these steps:
1) composer install (terminal command)
2) create environmental .env file based on .env.example(database, email config)
3) make migrations:
php artisan migrate --path=database/migrations/1
php artisan migrate --path=database/migrations/2
php artisan migrate --path=database/migrations/3
4) run command:
php artisan db:seed
 you are ready to go:
admin@cybersec.kz with same password for admin
editor1@cybersec.kz with same password for moderator
5) admin should add all fields that editor will fill in correct order
*) forget steps #4 and #5, restore cybersec scheme from cybersec.sql file to database, there are working example
6) php artisan storage:link
7) editor have to fill-in all forms