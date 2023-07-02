<strong>ServeHub</strong> is a project that helps restaurant businesses to automate their processes and bring them into the digital space.

Local setup:
- git clone https://github.com/Rudey-ua/digital-restaurant-automation.git
- composer install
- rename .env.example to .env
- set up a database connection in .env file
- php artisan key:generate
- php artisan migrate --seed

Dependencies:
- https://www.mailgun.com
- https://stripe.com
- https://www.twilio.com
  
Database schema:

![DB](https://github.com/Rudey-ua/digital-restaurant-automation/assets/72936853/4d68cfa6-3a10-4fb0-bb22-b5de2a303e18)

