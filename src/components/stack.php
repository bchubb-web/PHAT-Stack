<ul id='stack' class='w-96 h-192 grid grid-rows-<?= PARAMS[0] ?> gap-2 border-slate-500'>
    <?php for($i=0;$i<PARAMS[0];$i++) :
        HTMX::component('stack-empty');
    endfor; ?>
</ul>
