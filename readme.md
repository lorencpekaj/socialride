# Socialride

A project written for my Cloud Computing project at RMIT. It was mostly to aimed to utilise various cloud services hence why you will see an abundance of services being used.

## Getting Started

Once you have completely setup the local environment, deploying it is only a command away.

### Prerequisites

* PHP 7.2, Composer
* Google Cloud SDK (if you are planning to deploy to it)
* Google Cloud Project with APIs below enabled
    * Google Maps Javascript API
    * Google Directions
    * Google Places
* Facebook Application with Facebook Login enabled
* (optional) AWS RDS instance but you can use a local database

### Installing

1. Clone the repository
2. `cp .env.sample .env && cp app.yaml.example app.yaml`
3. `composer install && composer update`
4. `php artisan key:generate`
5. Edit the important environment variables inside `.env`
    1. `APP_...` fields adjust basic config of the app
    2. `DB_...` fields adjust the database
    3. `MAIL_...` fields adjust the mail service
    4. `FACEBOOK_...` fields adjust the facebook oauth
    5. `MIX_GOOGLE_MAPS_API_KEY` field sets the google maps api key
6. `npm install && npm run development` - will use the .env to create a dev build
7. `php artisan migrate:install` to create migrations table
8. `php artisan migrate:refresh --seed` to create all data structures and seed with dummy data
9. `php artisan serve` to run the application

## Deployment

Assuming you have installed the application on your local environment, you can deploy to google cloud easily.

1. Ensure that your `.env` fields are reflected in your `app.yaml`
    * For database config, you can specify your Amazon RDS database info
2. `gcloud auth login` if you are not authenticated already
3. `gcloud app deploy` inside the project directory
    * To specify a specific google cloud project id use the `--project={project-id}` parameter

The whole process of installation, migrating and seeding is completely automated while deploying to Google Cloud and hence will take several minutes to complete.

## Built With

* [Laravel 5.8](https://laravel.com/docs/5.8) - The PHP web framework used
* [VueJS](https://vuejs.org/) - Dependency Management

## Previews

#### Authentication page

![Login/Register Page](https://i.imgur.com/3gqnmii.png)

* Forgot password, register page all share similar design here

#### Dashboard

![Dashboard](https://i.imgur.com/0jsSlDL.png)

* All user locations are LIVE. If you move, you will move on the map for everyone.
* Trips are also updated live the moment you request etc.
* Seeded trips generate random coordinates in the city of Melbourne.
* Search bar has autocomplete for locations you wish to search for.

#### Live trip

![Live trip](https://i.imgur.com/qLsAvi1.png)

* Both the passenger and driver will see this screen.
* Locations are updated live (but untested for economical reasons).
* A = driver location, B = passenger location, C = final destination for passenger

## Authors

* Lorenc - [@zeelorenc](https://twitter.com/zeelorenc)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details