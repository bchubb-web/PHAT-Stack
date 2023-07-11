<?php
HTMX::put('/htmx/stack/push', function(){
    if ($_SESSION['pointer'] < $_SESSION['max'] -1)
        $_SESSION['pointer'] +=1;
    DOM::component('stack');
});

HTMX::post('/htmx/stack/pop', function(){
    if ($_SESSION['pointer'] > 0)
        $_SESSION['pointer']-=1;
    DOM::component('stack');
});

HTMX::post('/htmx/stack/reset', function(){
    $_SESSION['pointer'] = 0;
    DOM::component('stack');
});
