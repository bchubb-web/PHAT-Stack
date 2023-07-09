<ul id='stack' class='w-96 h-192 flex flex-col-reverse gap-2 border-slate-500'>
    <?php for($i=0;$i<$_SESSION['max'];$i++) :
        if($i < $_SESSION['pointer']){
            echo '<li class="full m-1 h-12 bg-slate-300"></li>';
        }
        else if ($i === $_SESSION['pointer']) {
            echo '<li class="pointer full m-1 h-12 bg-slate-300"></li>';
        }
        else {
            echo '<li class="empty m-1 h-12 border-2 border-slate-300"></li>';
        }
    endfor; ?>
</ul>
