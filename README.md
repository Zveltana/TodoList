# TodoList project

***

## Introduction

This project is part of OpenClassrooms' PHP/Symfony training program. You will find code designed with the Symfony framework.

This project involves enhancing an existing application for managing daily tasks.

The TodoList application was developed at full speed to show potential investors that the concept is viable.

The aim is to improve the quality of the application and perform the following tasks:
-   implement new functionalities ; 
-   the correction of a few anomalies ; 
-   implement automated tests.

Codacy's note :

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/f278286c67fb4c36a3fc5c472b1e8ed0)](https://app.codacy.com/gh/Zveltana/TodoList/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## Require

This project requires PHP 8.2, MySQL or MariaDB, Composer 2.5, Symfony 6.1 and Doctrine. The CSS framework used is
Bootstrap 5.2.3.

## For start

To start, you need to clone the main branch.

For the project to work well on your machine you need to do :

-   `npm install` to use nodes modules
-   `composer install` to generate a composer.json file

To configure the database, edit the `.env.local` file with your database connection data.

```php
DATABASE_URL="mysql://!ChangeMe!@localhost:3306/!ChangeMe!?serverVersion=mariadb-10.4.27&charset=utf8"
```

To create the database you need to run this command :

```php
symfony console doctrine:database:create
```

Then perform the migrations located in the `./migrations/` folder at the root of the project. This will allow you to get
the same database as me. To perform the migrations use this command :

```php
symfony console doctrine:migrations:migrate
```

After creating your database, you can also inject a dataset by performing the following command :

```php
symfony console doctrine:fixtures:load
```

The command to use to build the CSS is :
``` npm run build ```

To visualize the project in local, thanks to localhost it will be necessary to carry out this command :
``` symfony server:start ```

***
# The SnowTricks site is ready to be used !

## Connection

To log in to the administrator account :
-   username: `user+1`
-   password : `password`

To log in to the user account :
-   username : `user+6`
-   password : `password`
