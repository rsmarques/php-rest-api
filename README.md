# REST API

Simple RESTful API for data manipulation.

## Implementation

* Requests handled with PHP
* Google Firebase for data storage

## Requirements

In order to build the application a PHP web server is needed (e.g., [Apache](https://www.nginx.com/)), and a [Google Firebase](https://www.firebase.com/) account.

## Usage

1. Configure your Firebase credentials in /app/config/firebase.php
2. Define your models/controllers
3. composer install (for unit testing)
4. Application URL usage: /{modelName}⁄{id?}, according to [REST architecture](https://en.wikipedia.org/wiki/Representational_state_transfer)
5. You can test a running version [here](http://php-assessment.herokuapp.com)