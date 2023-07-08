<input class="border-2 border-slate-500 rounded-small" name="size"/>
<button class='bg-blue-500'
    hx-post="/htmx/stack/create"
    hx-target="section"
    hx-swap="innerHTML"
    hx-include="[name=size]"
>
    create stack
</button>
