<section class="flex items-center justify-center flex-col">
<?php if (count(PARAMS) > 0):

    if (!isset($_SESSION['max']) || PARAMS['size'] !== $_SESSION['max'] )
        $_SESSION['max'] = PARAMS['size'];

    if (PARAMS['size'] < $_SESSION['back']) {
        $diff = intval($_SESSION['back']) - intval($_SESSION['front']);
        $_SESSION['front'] = 0;
        if ($diff > intval(PARAMS['size'])-1)
            $_SESSION['back'] = intval(PARAMS['size'])-1;
        else
            $_SESSION['back'] = strval($diff);
    }

    if (!isset($_SESSION['front']))
        $_SESSION['front'] = 0;
    if (!isset($_SESSION['back']))
        $_SESSION['back'] = 0;

    DOM::component('queue');
    DOM::component('queue-buttons');
else:
    DOM::component('data-structure-init');
endif; ?>
</section>
