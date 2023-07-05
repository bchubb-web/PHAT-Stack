<?php
// HTMX

post('/htmx/remove', function(){
    echo "";
});

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
    <div class='flex justify-between align-center rounded border-2 border-slate-200 p-0.5 px-1'>
        <span><?=$_POST['input']?></span>
    
        <span class="flex gap-1">
            <span 
                hx-post="htmx/todo/edit"
                hx-swap="innerHTML"
                hx-vals='{"TODO":"<?=$_POST['input']?>"}'
                hx-target="closest div"

                class="cursor-pointer color-red"
            >edit</span>
            <span 
                hx-post="htmx/remove"
                hx-target="closest div"
                hx-swap="outerHTML"
                class="cursor-pointer color-red"
            >X</span>
        </span>
    </div>
<?php });

post('/htmx/todo/edit', function() { ?>
    
    <input class="border-2 border-slate-500 rounded-small" name="todo" value="<?= $_POST['TODO'] ?>"/>
    <span class="flex gap-1">
        <span 
            hx-post="htmx/todo/submit"
            hx-swap="innerHTML"
            hx-target="closest div"
            hx-include="[name=todo]"

            class="cursor-pointer color-red"
        >submit</span>
        <span 
            hx-post="htmx/remove"
            hx-target="closest div"
            hx-swap="outerHTML"
            class="cursor-pointer color-red"
        >X</span>
    </span>
<?php });

post('/htmx/todo/submit', function() { ?>
    <span><?= $_POST['todo'] ?></span>
    <span class="flex gap-1">
        <span 
            hx-post="htmx/todo/edit"
            hx-swap="innerHTML"
            hx-vals='{"TODO":"<?=$_POST['todo']?>"}'
            hx-target="closest div"

            class="cursor-pointer color-red"
        >edit</span>
        <span 
            hx-post="htmx/remove"
            hx-target="closest div"
            hx-swap="outerHTML"
            class="cursor-pointer color-red"
        >X</span>
    </span>
<?php });
