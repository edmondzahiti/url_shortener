# Url Shortener

## Installation

Clone the repo: ``` git clone https://github.com/edmondzahiti/url_shortener.git ```

```cd``` into the folder generated

Run ```copy .env.example .env``` and after that update database credentials in ```.env``` file

Execute commands as below:

```sh 
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

#### Unit Test

Execute command to run all the tests: ``` php artisan test ```  
Or Execute each test individually: ``` php artisan test --filter TestName ```  
