<div class='flex justify-between align-center rounded border-2 border-slate-200 p-0.5 px-1'>
    <span><?=$_POST['todo']?></span>
    <span class="flex gap-1">
        <span 
            hx-post="/htmx/todo/edit"
            hx-swap="outerHTML"
            hx-vals='{"todo":"<?=$_POST['todo']?>"}'
            hx-target="closest div"

            class="cursor-pointer color-red"
        >edit</span>
        <span 
            hx-post="/htmx/remove"
            hx-target="closest div"
            hx-swap="outerHTML"
            class="cursor-pointer color-red"
        >X</span>
    </span>
</div>
