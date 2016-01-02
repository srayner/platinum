Platinum Application
====================
Platinum is a conception of an ERP system written in PHP. It is currently work in progress.

Installation
------------
Installation of this application uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

Simply clone this repository, then install dependencies with composer.
```sh
git clone https://github.com/srayner/platinum.git
composer install
```
You next need to create a database. The application currently only supports MySQL, via the PDO driver.
Create a database called platinum, and a MySQL user with full access to it. Then execute the mysql.sql
script in the \data folder to build an empty database schema.

Rename the \config\autoload\local.php.dist file to \config\autoload\local.php
Then modify the username and password to match the MySQL user you just created.

You will then need to setup a virtual host on your webserver and point the document root to the
your-path/platinum/public folder.

Module Overview
---------------
Inventory
Sales
Purchasing
Manufacturing
Logistics
Finance

Inventory Module
----------------
Track stock by receipting goods in and booking stock out. Make stock transfers and
make manual adjustments.