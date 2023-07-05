<?php

function db_query($callback): bool{
    if(!is_callable($callback)){
        return false;
    }
    require(__DIR__.'/connection_details.php');
    $con = mysqli_connect()

}
