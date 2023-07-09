# phntm-Stack
A ghostly, lightweight stack designed to feel like magic; as if something (or someone) is doing it for you.

Using PHP to serve HTMX and styled with TailwindCSS, anything is possible.

## Routing
Routing built upon PHP router by https://github.com/phprouter/main.
Optimised to focus on purely GET requests to pages that exist in the pages/ directory.

Handled by Router class, providing dynamic route generation.

## Api
HTMX api endpoints are defined manually with the HTMX namespace, passing the route, with desired $variables in place in the uri, and a callback function, echoing HTML or escaping php tags to plain HTML

## NodeJS
Node is used for build tools (specifically laravel-mix), and TailwindCSS, as an easy interface to handle the boring things we dont want to think about

## Tailwind
TailwindCSS is generated at built time, to minimise bundle size.

## MySql
A Simple database, one that most started with, and for many projects you cant go wrong.
Whilst not currently implemented, i plan on adding some functionality to make common things that slight bit easier.
