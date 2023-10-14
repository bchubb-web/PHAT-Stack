# phntm
A lightweight framework designed to feel like magic; handling the boring bits of a modern web application, as if something (or someone) is doing it for you.

## PHP
Common PHP functionality is handled by a number of simple to use classes, providing anything you would require, and if needed can be extended yourself for full control.

Interactive components are served through simple api, and handled on the client side with HTMX.

## Routing
Routing built upon PHP router by https://github.com/phprouter/main.
Optimised to focus on purely GET requests to pages that exist in the pages/ directory.

Inspired by the app router from NextJS 13, dynamic route parts are denoted by enclosing the variable in \[square brackets\].

## Api
HTMX api endpoints are defined manually with the HTMX namespace, passing the route, with desired $variables in place in the uri, and a callback function, echoing HTML or escaping php tags to plain HTML

## Build
Node is used for build tools (specifically laravel-mix), and TailwindCSS, as an easy interface to handle the boring things we dont want to think about



## Tailwind
TailwindCSS is generated at built time, to minimise bundle size.

## MySql
