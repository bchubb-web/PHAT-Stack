# PHAT-Stack
The phattest, juciest stack for web dev, using PHP to serve HTMX and styled with TailwindCSS, anything is possible.

## Routing
Routing built upon PHP router by https://github.com/phprouter/main
Page routing is handled by Router class, providing dynamic route generation from the pages directory

HTMX api endpoints are defined manually with the HTMX namespace, passing the desired route, with $variables in place, and a callback function.

TailwindCSS are included on every page,

