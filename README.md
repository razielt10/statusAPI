# statusAPI

 Web Service in CakePhp 3.x, Managing short messages using Json to publish (Post), obtain
 list of this messagess (Get) or an message by id, delete a message using a email confirmation.
 
## Requirements

HTTP Server. For example: Apache. Having mod_rewrite is preferred, but by no means required.
PHP 5.5.9 or greater (including PHP 7).
mbstring PHP extension
intl PHP extension

More documentation in [CakePhp Book](http://book.cakephp.org/3.0/en/installation.html)
 
## Installation

Clone the project using git
<br>
<code>
$ git clone git@github.com:razielt10/statusAPI
</code>

Update repositories
<br>
<code>
$ composer update
</code>

Create the database using file database.sql

## Configuration

Read and edit `config/app.php` and setup the 'Datasources' to set the configuration to your database,
and 'EmailTransport' to your mail server, documentation in [cake book](http://book.cakephp.org/3.0/en/index.html) 

## Running the API

Run the cake server
<br>
<code>
$ ./statusAPI/bin/cake server
</code>

The API must be run in http://localhost:8765/

## Using the API

Read the documentation in RAML format, file StatusAPI.raml.

Can be use by any REST Client, we recommend use [Insomnia REST](https://insomnia.rest/documentation/).

## Logs and Errors

The errors and debug logging are based on CakePhp Core, documentation [Logging CakePhp](http://book.cakephp.org/3.0/en/core-libraries/logging.html).

These files are located in logs/ and there are debug.log and error.log

# Based on CakePHP Application Skeleton

A skeleton for creating applications with [CakePHP](http://cakephp.org) 3.x.

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).
