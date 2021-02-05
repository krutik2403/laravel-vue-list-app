## Postman Collection

import the `Laravel Vue Test.postman_collection.json` in Postman to test api.

## Installation

1. Install dependencies

```
composer install

npm install or yarn install
```

2. Copy `.env.example` to `.env` and setup database

3. Run server, migrate and seed

```
php artisan migrate --seed
php artisan key:generate
php artisan config:cache
php artisan serve
```

4. Compile frontend
```
npm run watch or yarn watch
```
