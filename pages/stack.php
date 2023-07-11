<?php if(isset($_GET['size'])){
    $_SESSION['pointer'] = 0;
    header('location: /stack/'.strval($_GET['size']));
} ?>

<section class="flex align-center justify-center flex-col">
<?php if (count(PARAMS) > 0) :

    if (!isset($_SESSION['max']) || PARAMS[0] !== $_SESSION['max'])
        $_SESSION['max'] = PARAMS[0];
    if (!isset($_SESSION['pointer']))
        $_SESSION['pointer'] = 0;
    if (PARAMS[0] < $_SESSION['pointer'])
        $_SESSION['pointer'] = intval(PARAMS[0])-1;

    DOM::component('stack');
    DOM::component('stack-buttons');
else:
    DOM::component('data-structure-init');
endif; ?>
</section>
