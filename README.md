Symfony 3 Simple Bootstrap
==========================

Welcome to this backbone of a Symfony project with Grunt and Compass.
It is meant to save some practises and tools I use often and keep track of my setup through time.
It's not perfect and might (will) contain mistakes.
If you ever see this project and want to comment, don't hesitate :)
It's meant to evolve with my learning and new projects I code.

If you want to understand more about Symfony and its structure,
go check [symfony.com][1] or  their [github][2]

This is just a backbone so it's pretty empty but when you code please comment your code thoroughly

What's inside?
--------------

The Symfony Standard Edition is configured with the following defaults:

  * An AppBundle you can use to start coding;

  * Twig as the only configured template engine;

  * Doctrine ORM/DBAL;

  * Swiftmailer;

  * Annotations enabled for everything.

Different bundles and technologies have been installed to add more features :

  * **Composer** - Manages PHP dependencies

  * **Doctrine Migrations** - Helps migrating database easily with keeping migration diff (in app/doctrineMigrations).
  See [Migrations Usage][3]

  * **KNPLabs Doctrine Behaviors** - Manages slugs, timestamps and translations.

  * [**EasyAdminBundle**][4] - Generates the backend with our different Entities.
  See app/config/admin.yml to see the configuration.
  I find it easier to use/install/manage than FOSUser Bundle and it's powerful for what I need usually

  * [**VichUploader**][5] - Manages image upload

  * [**Compass**][6] - Stylesheets were created in SASS (Scss format),
  Compass adds features to CSS and compiles SCSS -> CSS. Check documentation online to install it if you don't have it.
  It's based on Ruby so you have to install it too.
  See config.rb and config_dist.rb to see configuration of compass tasks

  * [**Grunt**][7] - JavaScript task runner to automate different things (minify css, js, images among others)
  See package.json for grunt dependencies and Gruntfile.js for configuration. Each tasks are explained inside.
  We use Symphony's environments to know if we should use dev files or distribution ones.
  web/dist directory hosts the distribution files (Minified css, images and js. Fonts)
  Remove, modify or add tasks as needed.

  * Git - Try to keep master branch on production and develop on test website. It keeps things easy to manage and to debug.
  Add tags when new releases are made to production

  * When possible, keep Entity queries in their respective repositories and query data with ArrayResults, it's way way faster than Object queries.
  Be careful that limit (Doctrine's maxresults) has a weird (and broken) behaviour with ArrayResult. See Doctrine Documentation.

Installation
--------------

#### PHP
After pulling the website from git and installing the different technologies (php, ruby, compass, grunt, ...), you should :
```
    php composer install
```
 If you add new php dependencies or bundles :
```
    php composer update
```

 Configure parameter.yml with your database and smtp settings
 Install database :
```
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force
```

  When making changes to database, use database migration, not schema update

```
doctrine:migrations
  :diff     Generate a migration by comparing your current database to your mapping information.
  :execute  Execute a single migration version up or down manually.
  :generate Generate a blank migration class.
  :migrate  Execute a migration to a specified version or the latest available version.
  :status   View the status of a set of migrations.
  :version  Manually add and delete migration versions from the version table.
```

  For cleaning cache in Symfony use :
```
    php bin/console cache:clear
```

  And for dist environment :
```
    php bin/console cache:clear --env=prod
```

It usually fixes many problems to clear the cache (add translations, ...)

#### Tasks

Tasks are run through Grunt and explained in the Gruntfile.js
For dev environment, you can just use :
```
    grunt watch
```
It will watch for changes on scss files, compile them into one css file and add post-processors automatically

On production, you need to run :

```
    grunt dist
```

It will run each steps needed by the prod environment, check js and compile everything
Dist folder is ignored by git
Add or remove what you need/don't need


If you add new features, dependencies or stuffs :), document and comment it please.
**Symfony**'s documentation is very good so you can refer to it anytime you are lost.

Enjoy!

[1]:  https://symfony.com
[2]:  https://github.com/symfony/symfony
[3]:  https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html#usage
[4]:  https://github.com/javiereguiluz/EasyAdminBundle
[5]:  https://github.com/dustin10/VichUploaderBundle/
[6]:  http://compass-style.org/
[7]:  http://gruntjs.com/

