<?php
if (!function_exists('db_query')){
    function db_query($callback): mysqli_result | bool{
        if(!is_callable($callback)){
            return false;
        }
        require(__DIR__.'/connection_details.php');

        $res = call_user_func($callback);
        mysqli_close($con);
        return $res;
    }
}
