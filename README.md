## Metrotrack

This application serves as a way to easily track all of the public busses of Metrobus in St. John's Newfoundland and the surrounding area. 

This project was built by Adam Day who is not affiliated, associated, endorsed by, or in anyway officially connected with Metrobus. The official Metrobus website can be found at https://metrobus.com

This application was born from boredom and curiosity and is not intended to generate any financial gain for its creator.

#### Installation

This project was built using the PHP micro-framework Lumen.

To install your own instance of this application, follw these steps:

- clone the repository
- run `composer install`
- run `cp .env.example .env`
- run `php artisan key:generate`
- add your own google maps key to the .env file
