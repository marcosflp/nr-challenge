# Negocios Reais Challenge
A simple web Scraper using Laravel and Goutte to crawl data from cnpq.br.

## Requirements
* PHP >= 5.6.4
* Composer >= 1.3

## Installation
```bash
git clone https://github.com/marcosflp/nr-challenge.git
cd nr-challenge
php composer install
cp .env.example .env
php artisan key:generate

```

## Usage
Using a configured web server you can make requests to the http://{host}/nr-challenge/public/ and 
get the data parsed into json.

Route configured: '/cnpq/1'
