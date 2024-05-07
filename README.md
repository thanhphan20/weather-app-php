## Weather Application <br>

First you need to clone my repository

```
git clone https://github.com/thanhphan20/weather-app-php.git
```

After that go to the directory by typing the command shown below.

```
cd weather-app-php
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

But first you need to setup the application remember to config the .env file

The DB_HOST you must use the DB_HOST=host.docker.internal to run app on docker server

```
docker-compose up -d --build
```

The bash command will run to install the package and migrate the database

So it will take a moment to complete

### Thanks for reading :heart:
### Have a nice day :heart: