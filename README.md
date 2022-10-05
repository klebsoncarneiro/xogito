# xogito
User API in PHP

# October 2022
- PHP task - User API

- Runs on PHP 7+
- Uses PostgreSQL for persistence and supports JSON request payloads. 
- Has user accounts that can log in to the system using MFA method. 
- Each account can be either an Administrator or a User.
- Runs on Pure PHP (no framework)
- Users can complete the registration step
- Users can login
- Users can update their name
- Admin users can do everything a User can do
- Admin can create a new account (Users and Administrators)
- Admin can deactivate an account

## Dependencies

- "mailjet/mailjet-apiv3-php": "^1.5",
- "ext-curl": "*"-   

## Install

You can download this repository to your machine through `git clone` in your localhost root:
```
git clone https://github.com/klebson.carneiro/xogito
```
Composer installation for the API:
```
cd xogito
composer update
composer require mailjet/mailjet-apiv3-php
composer require ext-curl
```
Final composer.json:
```
{
    "autoload":{
        "psr-4":{
            "App\\":"App"
        },
        "files":[
            "config.env.php"
        ]
    },
    "require":{
        "mailjet/mailjet-apiv3-php": "^1.5",
        "ext-curl": "*"
    }
}
```
## Database

- A PostgreSQL database is used for this example*
- config.env.php.example supplied
- (*) Ask for the config.env.php

Table needed:
```
create table user_account (
    id serial not null,
    name varchar not null,
    email varchar not null,
    password varchar not null,
    active boolean not null default true,
    mfa_code int null,
    is_admin boolean not null default false
);
```
You need an admin account to start using the system with a valid e-mail to get the MFA Code:
```
insert into user_account (name, email, password, is_admin) values ('Admin', 'adminemail@adminemail.com', 'your_password', true);
```
## Mailer

- Mailjet is used for this example*
- config.env.php.example supplied
- (*) Ask for the config.env.php

## config.env.php

- You need to fill up these fields for PostgreSQL, Mailjet and JWT secret*
- config.env.php.example supplied
- (*) Ask for the config.env.php

```
const DBDRIVE = "pgsql";
const DBHOST = "***";
const DBNAME = "***";
const DBUSER = "***";
const DBPASS = "***";

const MAILJET_APIKEY = "***";
const MAILJET_SECRETKEY = "***";

const TOKEN_SECRET = "***";
```

## Postman request collection for the API
- https://github.com/klebsoncarneiro/xogito/blob/main/XogitoAPI.postman_collection.json

## How to use
- Main page: 
```
http://localhost/xogito/
```
