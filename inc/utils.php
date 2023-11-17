<?php

/**
 * Dumps the content of a variable and format
 */
function dump($thing){
    echo '<pre>';
    var_dump($thing);
    echo '</pre>';
}

/**
 * redirects the page, even after headers have been sent
 */
function redirect(string $url) { ?>
    <script>window.location="<?=$url?>"</script>
<?php }
