<!-- start vars
$id
$edit
end vars -->
<?php if(isset($params)){
    var_dump($params);
    echo '<h1>'.$id.'</h1>';
}
else {
    echo '<h1>No User</h1>';
}
