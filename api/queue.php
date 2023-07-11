<?php
HTMX::put('/htmx/queue/enqueue', function(){
    if ($_SESSION['back'] < $_SESSION['max'] -1)
        $_SESSION['back'] +=1;
    DOM::component('queue');
});

HTMX::post('/htmx/queue/dequeue', function(){
    if ($_SESSION['front']+1 < $_SESSION['back'])
        $_SESSION['front']+=1;
    DOM::component('queue');
});

HTMX::post('/htmx/queue/reset', function(){
    $_SESSION['front'] = 0;
    $_SESSION['back'] = 0;
    DOM::component('queue');
});
