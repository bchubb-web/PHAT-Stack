<?php

HTMX::post('/htmx/todo/add', function(){
    HTMX::component('todo-static');
});

HTMX::post('/htmx/todo/edit', function() { 
    HTMX::component('todo-input');
});

HTMX::post('/htmx/todo/submit', function() {
    if ($_POST['todo'] !== '') {
        HTMX::component('todo-static');
    }
});
