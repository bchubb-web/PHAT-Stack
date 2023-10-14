<section class="flex mt-8 items-center justify-center flex-col">
<?php if (count(PARAMS) > 0) :

    if (!isset($_SESSION['max']) || PARAMS['size'] !== $_SESSION['max'])
        $_SESSION['max'] = PARAMS['size'];
    if (!isset($_SESSION['pointer']))
        $_SESSION['pointer'] = 0;
    if (PARAMS['size'] < $_SESSION['pointer'])
        $_SESSION['pointer'] = intval(PARAMS['size'])-1;

    DOM::component('stack');
    DOM::component('stack-buttons');
else:
    DOM::component('data-structure-init');
endif; ?>
</section>
