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
RewriteRule (.*) index.php [QSA,L]
```

Obviously you must then add index.php in the base of your project, to start, add the following contents:
```php
<?php

namespace bchubbweb\PhntmImplementation;

require('vendor/autoload.php');

use bchubbweb\phntm\Phntm;

// remove 'profile: true' to disable profiling
Phntm::init(profile: true);
```

This will set up the namespace router, and determine what to do with the current request.

Next, add the Pages namespace to your composer.json pointing to the directory you wish to use, although I would recommend pages/ in the base of your project.
```json
{
    "autoload": {
        "psr-4": {
            "Pages\\": "pages/"
        }
    }
}
```

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
            <h1>home</h1>
        HTML);
    }
}
```

## Layouts

Similar to NextJS, phntm uses layouts to wrap around the content of a page, this is useful for things like headers and footers, shared assets and more.
```php
<?php

namespace Pages;
 
use bchubbweb\phntm\Resources\Layout as LayoutTemplate;

class Layout extends LayoutTemplate
{
    public function __construct() {

        $this->registerAsset('/assets/layout.css');
        $this->registerAsset('/assets/layout.js');

        $this->setContent(<<<HTML
        <html>
            <head>
                <title>Phntm</title>
                <!-- head /-->
            </head>
            <body>
                <header>
                    <nav>
                        <ul>
                            <li><a href="/">Home</a></li>
                            <li><a href="/about">About</a></li>
                            <li><a href="/users">Users</a></li>
                        </ul>
                    </nav>
                </header>
                <main>
                    <!-- content /-->
                </main>
                <footer>
                    <p>Phntm</p>
                </footer>
                <!-- profiler /-->
            </body>
        </html>
        HTML);
    }
}
```
The comments are used to inject the content of the page into the layout, registered assets and the profiler script if enabled.

Unlike NextJS, layouts are limited to one deep, so the closest layout in the namespace for a given page will be used.

## Profiling

To use the phntm profiler pass true to the Phntm::init() function, this will profile the request and inject the profiler script into the layout.

The script then fetches the cached profile data from the /api/profiler endpoint, and displays it in a table.
