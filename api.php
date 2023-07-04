<?php
// HTMX

get('/htmx/callback', function(){
  echo 'Callback executed';
});

get('/htmx/callback/$name', function($name){
  echo "Callback executed. The name is $name";
});

post('/htmx/date', function(){
     echo date("h:i:sa");
});

post('/htmx/todo/add', function(){ ?>
    <div class='flex justify-between rounded border-2 border-slate-200 p-0.5'>
        <span><?=$_POST['input']?></span>
        <span 
            hx-post="htmx/todo/remove"
            hx-target="closest div"
            hx-swap="outerHTML"
        >X</span>
    </div>
<?php });

post('/htmx/todo/remove', function(){
    echo "";
});

get('/htmx/callback/$name/$last_name', function($name, $last_name){
  echo "Callback executed. The full name is $name $last_name";
});
