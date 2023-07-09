<?php
HTMX::put('/htmx/stack/push', function(){
    session_start();
    if ($_SESSION['pointer'] < $_SESSION['max'] -1)
        $_SESSION['pointer'] +=1;
    HTMX::component('stack');
});

HTMX::post('/htmx/stack/pop', function(){
    session_start();
    if ($_SESSION['pointer'] > 0)
        $_SESSION['pointer']-=1;
    HTMX::component('stack');
});

HTMX::post('/htmx/stack/create', function(){
    HTMX::component('stack');
});

