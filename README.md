REFRESH LOCAL DB AND RESEED `php artisan migrate:fresh --seed`

PUT ALL TS FILES UNDER `/resources/ts`

PUT ALL FRONTEND VIEWS UNDER `/resources/views` and the file format is `*.blade.php`
for all blade files, import corresponding ts script with `@vite('resources/ts/yourScript.ts')` under `<head>`

NEW ADMIN ROUTES AND API GO UNDER `routes/web.php`

** for admin routes and api's they all go under web.php
api.php will come later when we work on non-admin features
~~NEW API GO UNDER `routes/api.php`~~

run using `npm run dev`


If need more admin users for testing: use `php artisan db:seed --class=RandomAdminsSeeder` to quickly add 15 random admins to the db