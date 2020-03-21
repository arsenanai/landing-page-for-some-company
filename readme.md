# CMS Admin panel for landing page

## Software requirements
- composer(v1.3.2), 
- php(v7.1.4(used), v5.6(min for laravel)), 
- mysql(v5.7 min for JSON datatype) - database schema named "database" with collation "utf8_unicode_ci", 
- laravel(v5.4.18, installer v1.3.5)

## to install make these steps
- ``composer install``
- create environmental .env file based on .env.example(database, email config)
- make migrations:
``php artisan migrate --path=database/migrations/1``
``php artisan migrate --path=database/migrations/2``
``php artisan migrate --path=database/migrations/3``
- run command:
``php artisan db:seed``

### you are ready to go, users are:
- admin@abc.xyz with same password for admin
- editor1@abc.xyz with same password for moderator
- admin should add all fields that editor will fill in correct order
- forget steps #4 and #5, restore cybersec scheme from database.sql file to database, there are working example
- ``php artisan storage:link``
- editor have to fill-in all forms