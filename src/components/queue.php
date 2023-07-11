<ul id='queue' class='w-auto h-192 flex flex-row gap-2 border-slate-500'>
    <?php for($i=0;$i<$_SESSION['max'];$i++) :
        if($i < $_SESSION['front']){
            echo '<li class="empty m-1 w-12 h-64 border-2 border-slate-300"></li>';
        }
        else if ($i <= $_SESSION['back']) {
            echo '<li class="full m-1 w-12 h-64 bg-slate-300 border-2 border-slate-300"></li>';
        }
        else {
            echo '<li class="empty m-1 w-12 h-64 border-2 border-slate-300"></li>';
        }
    endfor; ?>
</ul>
