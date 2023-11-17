<?php 
/*if(isset($_GET['size'])){
    $_SESSION['front'] = 0;
    $_SESSION['back'] = 0;
    header('location: /examples/queue/'.strval($_GET['size']));
} */
?>
<h1>Select size</h1>
<form action="/examples/queue" method="get">
    <input name="size" type="range" min="3" max="20" />
    <button type="submit">Generate</button>
</form>
