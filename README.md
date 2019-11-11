# Marky

## Setup

```
git clone https://github.com/linnit/marky.git
cd marky
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## Usage

Load sample text:

```
php bin/console app:load-messages ./samples/Catch-22_Chapter-1-excerpt.txt
```

Create new message:

```
php bin/console app:create-message
```

## ToDo

 * Have a weight on the values, possibly on the count of occurrences
