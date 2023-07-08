<?php
HTMX::post('/htmx/remove', function(){});


HTMX::get('/htmx/callback/$name', function($name){
  echo "Callback executed. The name is $name";
});
