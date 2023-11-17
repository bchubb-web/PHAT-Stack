<?php 
if(isset($_GET['size'])){
    $_SESSION['pointer'] = 0;
    redirect("/examples/stack/".strval($_GET['size'])."/");
}
?>

<h1>Select size</h1>
<form action="/examples/stack" method="get">
    <input name="size" type="range" min="3" max="20" />
    <button type="submit">Generate</button>
</form>
