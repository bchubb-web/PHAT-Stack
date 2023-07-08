<?php
HTMX::put('/htmx/stack/push', function(){
    HTMX::component('stack-element');
});

HTMX::post('/htmx/stack/pop', function(){
    HTMX::component('stack-empty');
});

HTMX::post('/htmx/stack/create', function(){
    HTMX::component('stack');
});

