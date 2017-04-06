Password Manager for teams
========================

The "Password Manager for teams" is a simple software that allows to organize passwords in different folders which can be attributed to users in a team.
There is a possibility to hide and unhide folders, and different groups can be opened.
Passwords can be copied directly from the interface without showing it.

Requirements
------------

  * PHP 5.5.9 or higher
  * MYSQL Server installed
  * [usual Symfony application requirements](http://symfony.com/doc/current/reference/requirements.html).


Installation
------------

First, install the [Symfony Installer](https://github.com/symfony/symfony-installer)
if you haven't already. Then, install the Symfony Demo Application executing
this command anywhere in your system:

```bash
$ git clone https://github.com/benoitfrisch/PasswordManager.git
$ cd password-manager
```
You have to install [composer](https://getcomposer.org/download/) on your computer if you don't have it already.

After installing composer run following command:
```bash
$ ./composer.phar install
```
Please update your MYSQL Settings in the parameters.yml file.
You can now generate the database schemas and create a first user, which will be promoted to Admin.
```bash
$ bin/console doctrine:schema:create 
$ bin/console fos:user:create
$ bin/console fos:user:promote [created username] ROLE_ADMIN
```

Usage
-----

If you want to use a fully-featured web server (like Nginx or Apache) to run the application, configure it to point at the `web/` directory of the project.
For more details, see:
http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
http://symfony.com/doc/current/deployment.html

You can access the application from the URL you installed it on.