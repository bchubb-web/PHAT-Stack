<div class='flex justify-between align-center rounded border-2 border-slate-200 p-0.5 px-1'>
    <input class="border-2 border-slate-500 rounded-small" name="todo" value="<?= $_POST['todo'] ?>"/>
    <span class="flex gap-1">
        <span 
            hx-post="/htmx/todo/submit"
            hx-swap="outerHTML"
            hx-target="closest div"
            hx-include="[name=todo]"
            class="cursor-pointer color-red"
        >submit</span>
        <span 
            hx-post="/htmx/remove"
            hx-target="closest div"
            hx-swap="outerHTML"
            class="cursor-pointer color-red"
        >X</span>
    </span>
</div>
