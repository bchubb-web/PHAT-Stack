<?php

HTMX::post('/htmx/todo/add', function(){
    DOM::component('todo-static');
});

HTMX::post('/htmx/todo/edit', function() { 
    DOM::component('todo-input');
});

HTMX::post('/htmx/todo/submit', function() {
    if ($_POST['todo'] !== '') {
        DOM::component('todo-static');
    }
});
