# phntm
A lightweight framework designed to feel like magic; handling the boring bits of a modern web application, as if something (or someone) is doing it for you.

## Installation

To install require the package to your project:
```bash
composer require bchubbweb/phntm
```

## Setup

Create a .htaccess file with the following contents at the base of your project
```
RewriteEngine On
RewriteCond %{REQUEST_URI}  !(\.png|\.jpg|\.webp|\.gif|\.jpeg|\.zip|\.css|\.svg|\.js|\.pdf|\.ttf)$
RewriteRule (.*) setup.php [QSA,L]
```

Obviously you must then add setup.php in the base of your project, to start, add the following contents:
```php
<?php

require('vendor/autoload.php');

use bchubbweb\phntm\Routing\Router;

$router = new Router();

$route = Router::getRequestedRoute();

$router->determine($route);
```

This will set up the namespace router, and determine what to do with the current request.

Next, add the pages namespace to your composer.json pointing to the directory you wish to use, although I would recommend pages/ in the base of your project.

To ensure composer loads all the contents of this directory call the following command to force this.
```bash
composer dumpautoload --optimize
```

Any time you add a new page or file to your pages/ directory you will need to call this to resource the namespace
 - I am currently looking into a way to do this automatically

Then start your local server and do as you wish
```bash
php -S localhost:3000
```

## Pages

pages within your pages directory should extend the bchubbweb\phntm\Resource\Page class, and have to be named Page.php, this lets the router know this namespace can be routed to.
```php
<?php

namespace Pages\Home;

use bchubbweb\phntm\Resources\Page as PageTemplate;

class Page extends PageTemplate
{
    public function __construct() {
        parent::__construct();

        $this->setContent(<<<HTML
        <html>
            <body>
                <h1>home</h1>
            </body>
        </html>
        HTML);
    }
}
```
