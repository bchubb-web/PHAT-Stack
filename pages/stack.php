<section class="flex align-center justify-center flex-col">
<?php if (count(PARAMS) > 0) {

    session_start();

    if (!isset($_SESSION['max']))
        $_SESSION['max'] = PARAMS[0];

    if (!isset($_SESSION['pointer']))
        $_SESSION['pointer'] = 0;

    HTMX::component('stack');
    HTMX::component('stack-buttons');
} ?>
</section>
