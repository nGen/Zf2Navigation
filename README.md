Zf2Navigation
=============

Dynamic Navigation System for Zend Framework 2.

Introduction
------------

The navigation component of Zend Framework 2 has a simple approach. It either uses config files with Array definition of menus or XML files with similar data to render or generate the menu. However, the most popular way in the web is to generate or get the menu items from db and manage them through a manageable interface. All popular CMS's like Wordpress, Joomla have this feature but building such menu with complex hierarchy is a huge performance load on any application. Zend Framework 2's Navigation component uses static definition of link for this very reasons.

Most of the solutions on the internet provide you with a way to get the list of menu menu at startup but as I mentioned earlier it comes in the cost of huge performance load. This library however gives you the option to have a database managed Navigation System as well as remove the performance load by generating the navigation configuration file with a click of a button.

Requirements
------------

* [ZfcBase](https://github.com/ZF-Commons/ZfcBase) (latest master).
* [nGenZfc](https://github.com/nGen/Zfc) (latest master)
* [TwbBundle](https://github.com/neilime/zf2-twb-bundle) (lastest master)

Features
--------

* Create new navigation containers without havign to create a Navigation Factory file everytime new menu should be created.
* Navigation Management system with various features to create new containers as "Navigation Menus" and add various pages or link on them dynamically.
* Three modes of every menu: Menu, Breadcrumbs and Sitemap. You can choose to select which link to appear where as required.
* Create Navigation Page on simple mode providing directly only the link or go to advanced mode and add all sorts of technical details like route and its parameters, target, position etc.
* Management system of these menus and links with full functional CRUD features including enabling and disabling them.
* Mockup configuration to directly embed the navigation system within the admin route. It is totally optional.
* Generates the Navigation Factory and Navigationa Config for all the menu.
* Full functional DB tables that supports all properties of ZF2's NavigationPage Object.

Installation
------------

### Main Setup

#### With composer

1. Add this project and [ZfcBase](https://github.com/ZF-Commons/ZfcBase) in your composer.json:

    ```json
    "require": {
        "ngen/zf2navigation": "dev-master",
    }
    ```

2. Now tell composer to download ZfcUser by running the command:

    ```bash
    $ php composer.phar update
    ```

#### By cloning project

1. Install the [ZF2Navigation](https://github.com/nGen/Zf2Navigation) ZF2 module
   by cloning it into `./vendor/`.
2. Clone this project into your `./vendor/` directory.


### Post installation

1. Enabling it in your `application.config.php`file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'ZfcBase',
            'nGen\Zfc',
            'nGen\Zf2Navigation',
		    'TwbBundle',
        ),
        // ...
    );
    ```

2. Then Import the SQL schema located in `./vendor/ngen/zf2navigation/data/schema.sql` (if you installed using the Composer) or in `./vendor/zf2navigation/data/schema.sql`.

3. Copy the `nav.global.php` from the `./vendor/ngen/zf2navigation/config` directory into `config/autoload` directory and give it 777 permission.

4. Copy either `zf2navigation.default.global.php.dist` or `zf2navigation.admin.global.php.dist` from the `./vendor/ngen/zf2navigation/config` directory into `config/autoload` directory and remove the `.dist` at the end of the file. 
    * `zf2navigation.admin.global.php.dist`: It contains route configuration with which you can directly embed the navigation management system into the admin route.
    * `zf2navigation.admin.global.php.dist`: It contains default route configuration.
    
5. Update the `base_path` with location to your project root (not public directory), absolute path is preferred. It is used by the library to find the nav.global.php to write the configuration settings on.

6. Lastly, it should have a working db connection to start creating and adding menus. Go ahead and configure DB Adapter for that. 

7. We are all set, navigation to `project/path/navigation` or `/project/path/admin/navigation` depending on with configuration you used.

Author
------

[Starx](http://mrnepal.com)

[Project Site](http://ngeneric.com)

