<strong>ServeHub</strong> is a project that helps restaurant businesses to automate their processes and bring them into the digital space.

Local setup:
- git clone https://github.com/Rudey-ua/digital-restaurant-automation.git
- composer install
- rename .env.example to .env
- set up a database connection in .env file
- complete account information in .env for all required services (Stripe, Mailgun, Twillo) 
- php artisan key:generate
- php artisan migrate --seed
  
Database schema:
![photo_2023-06-23_19-34-14](https://github.com/Rudey-ua/digital-restaurant-automation/assets/72936853/01e88a85-2068-44c8-aaf9-857c1830ad0a)
