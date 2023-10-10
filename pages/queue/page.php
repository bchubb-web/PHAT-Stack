<?php if(isset($_GET['size'])){
    $_SESSION['front'] = 0;
    $_SESSION['back'] = 0;
    header('location: /queue/'.strval($_GET['size']));
} ?>
