<?php

require_once __DIR__.'/router.php';

require_once __DIR__.'/head.php';


//pages
get('/', 'views/index.php');

get('/user/$id', 'views/user');

get('/user/$name/$last_name', 'views/full_name.php');

get('/product/$type/color/$color', 'product.php');


any('/404','views/404.php');




// HTMX


get('/htmx/callback', function(){
  echo 'Callback executed';
});

get('/htmx/callback/$name', function($name){
  echo "Callback executed. The name is $name";
});

get('/htmx/product', '');

get('/htmx/callback/$name/$last_name', function($name, $last_name){
  echo "Callback executed. The full name is $name $last_name";
});


