<?php if(isset($_GET['size'])){
    $_SESSION['front'] = 0;
    $_SESSION['back'] = 0;
    header('location: /queue/'.$_GET['size']);
} ?>

<section class="flex align-center justify-center flex-col">
<?php if (count(PARAMS) > 0):

    if (!isset($_SESSION['max']) || PARAMS[0] !== $_SESSION['max'] )
        $_SESSION['max'] = PARAMS[0];

    if (PARAMS[0] < $_SESSION['back']) {
        $diff = intval($_SESSION['back']) - intval($_SESSION['front']);
        $_SESSION['front'] = 0;
        if ($diff > PARAMS[0]-1)
            $_SESSION['back'] = PARAMS[0]-1;
        else
            $_SESSION['back'] = strval($diff);
    }

    if (!isset($_SESSION['front']))
        $_SESSION['front'] = 0;
    if (!isset($_SESSION['back']))
        $_SESSION['back'] = 0;

    HTMX::component('queue');
    HTMX::component('queue-buttons');
else:
    HTMX::component('data-structure-init');
endif; ?>
</section>
