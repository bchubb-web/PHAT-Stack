<?php 
if(isset($_GET['size'])){
    $_SESSION['pointer'] = 0;
    header('location: /stack/'.strval($_GET['size']));
}
?>
