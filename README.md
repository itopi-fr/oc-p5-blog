# oc-p5-blog



## Code Quality
Insérer le badge codacy ici


## Description

This project is a blog written in PHP.
The available main functions are :

### Back Office

  - Create, edit, publish posts
  - Validate or refuse comments

### Front Office

  - Register / Login / Logout
  - Display a list of posts
  - Display a single post
  - Comment a post



## Requirements
### PHP
This project requires PHP 8.0 or higher.

### MySQL
This project requires MySQL 8.0 or higher.

### Composer
This project requires Composer 2.0 or higher.


## Services and extensions used
This project uses the following services and extensions :

### Composer extensions
  - [twig/twig](https://packagist.org/packages/twig/twig)
  - ext-pdo
  - [vlucas/phpdotenv](https://packagist.org/packages/vlucas/phpdotenv)
  - [phpmailer/phpmailer](https://packagist.org/packages/phpmailer/phpmailer)

### Libraries
- [Bootstrap  v5.3.0-alpha1](https://getbootstrap.com/)
- [fontawesome](https://fontawesome.com/)


## Installation

### Clone the repository

```bash
git clone itopi-fr/oc-p5-blog
```

### Install MySQL database
Use the script `p5blog.sql`

### Install dependencies
```bash
composer install
```

### Configure the environment variables
Rename the file `.env.sample` to `.env` and edit it with your database and SMTP information.

### Virtual Host
Configure your virtual host to point to the root directory of the project (where this `readme.md` file is located).
