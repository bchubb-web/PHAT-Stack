<div class='flex gap-4 justify-center'>
    <button class='bg-green-500 w-16'
        hx-put="/htmx/stack/push"
        hx-target="#stack"
        hx-swap="innerHTML"
    >push</button>

    <button class='bg-red-500 w-16'
        hx-post="/htmx/stack/pop"
        hx-target="#stack"
        hx-swap="innerHTML"
    >pop</button>
</div>
