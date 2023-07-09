<section class="flex align-center justify-center flex-col">
<?php if (count(PARAMS) > 0) {

    session_start();

    if (!isset($_SESSION['max']))
        $_SESSION['max'] = PARAMS[0];
    else if(PARAMS[0] > $_SESSION['max']) {
        $_SESSION['max'] = PARAMS[0];
    }

    if (!isset($_SESSION['front']))
        $_SESSION['front'] = 0;
    if (!isset($_SESSION['back']))
        $_SESSION['back'] = 0;

    HTMX::component('queue');
    HTMX::component('queue-buttons');
} ?>
</section>
