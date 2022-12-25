# MovieDB

A web-based application that facilitates users access to information like rating, trailer, feedback related to the popular movies stored in the database. There is also the option of watching favourite movies on a third party platform.

## Requirements
* Apache or Nginx
* PHP version 5.6 or greater
* MySQL version 5.7 or greater

## Installation
1. Download and unzip the website package
2. Copy all files and folders in the root directory of your website
3. Create the database on your own web server
4. Import `database.sql` to your previously created database
5. Access `http://omdbapi.com/apikey.aspx` and generate an API Key
6. Open `your_root_directory/includes/config.php` and modify the following variables:
 * SITETITLE - your website title (eg: MovieDB)
 * OMDB_API - the API Key generated at Step 5
 * DBHOST - database host (eg: localhost)
 * DBUSER - database username (eg: root)
 * DBPASS - database password
 * DBNAME - database name (eg: moviedb)
7. CHMOD 0777 the following file `counter.txt`

## Technologies
* Programming languages: PHP, HTML
* Frameworks: Bootstrap
* Protocols: HTTP
* Databases: MySQL
* Data formats: XML
* Open source solutions: [Free-PHP-Counter](http://www.free-php-counter.com), [Disqus](https://disqus.com)

## Demo
Link: `http://unn-w18038124.newnumyspace.co.uk/moviedb/`

#### User account
* username: test
* password: 123

#### Admin account
* username: admin
* password: 1234
