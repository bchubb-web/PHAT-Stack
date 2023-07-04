<?php
//pages
get('/', 'pages/home.php');

get('/PHAT', 'pages/home.php');

get('/todo', 'pages/todo.php');

get('/user/$id', 'pages/user.php');

get('/user/$name/$last_name', 'pages/full_name.php');

get('/product/$type/color/$color', 'pages/product.php');

