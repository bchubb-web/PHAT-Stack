<section class="flex justify-center align-center">
    <div class="w-96">
        <form
            class="flex justify-between gap-8"
            hx-post="/htmx/todo/add"
            hx-trigger="submit"
            hx-target="#todo-list"
            hx-swap="afterbegin"
        >
            <input placeholder="enter todo"
                class="border-2 border-slate-200 w-full"
                name="input"
            />
            <button type="submit" class="rounded border-2 border-slate-200 bg-slate-50 px-2" >Add</button>
        </form>
        <div id="todo-list" class="flex flex-col gap-1">
        </div>
    </div>
</section>
