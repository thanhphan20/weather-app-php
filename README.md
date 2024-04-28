## Clone My Repo <br>

After Cloning, Go to the directory by typing the command shown below.

```
cd weather-app-php && cd laravel-app
```

Then create `.env` file on root directory

```
cp .env.example .env
```

Then install package and run package

```
composer install
```

Then generate key to set APP_KEY value

```
php artisan key:generate
```

After migrate database

```
php artisan migrate
```

Finally run and visit `http://127.0.0.1:8000/`

```
php artisan serve
```

Or you can use the docker command

```
docker-compose up -d --build
```

And then setup the application remember to config the .env file

```
docker compose exec php bash
composer install
php artisan key:generate
php artisan migrate
```

### Thanks for reading :heart:
### Have a nice day :heart: