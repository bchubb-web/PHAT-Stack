<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link type="text/javascript" rel="preload" href="/dist/main.min.js" as="script"/>
        <?= DOM::fonts(); ?>
        <script  src="https://cdn.tailwindcss.com"></script>
        <title><?= Router::$page; ?></title>
        
    </head>
    <body>
        <?php DOM::styles(); ?>
        <header style='font-family: "Chela";' class="bg-neutral-900">
            <div class="mx-auto px-8 py-6 max-w-6xl flex justify-between gap-8">
                <h1 style='font-family: "Chela";' class="text-emerald-400 text-2xl"><a href="/">phntm</a> <span class="text-slate-200 text-xl">- <i>freakishly</i> lightweight</span></h1>
                <nav>
                    <ul class="flex justify-between gap-4 ">
                        <li><a class="text-emerald-400 hover:text-emerald-200" href="/examples">Examples</a></li>
                        <li><a class="text-emerald-400 hover:text-emerald-200" href="/docs">Docs</a></li>
                        <li><a class="text-emerald-400 hover:text-emerald-200" target="_blank" href="https://github.com/bchubb-web/phntm-stack">Repo</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main class="relative">
        <?php Raise::notify(); ?>
