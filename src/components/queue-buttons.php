<div class='flex gap-4 justify-center'>
    <button class='bg-green-500 w-16'
        hx-put="/htmx/queue/enqueue"
        hx-target="#queue"
        hx-swap="outerHTML"
    >enqueue</button>

    <button class='bg-red-500 w-16'
        hx-post="/htmx/queue/dequeue"
        hx-target="#queue"
        hx-swap="outerHTML"
    >dequeue</button>

    <button class='bg-red-500 w-16'
        hx-post="/htmx/queue/reset"
        hx-target="#queue"
        hx-swap="outerHTML"
    >reset</button>
</div>
