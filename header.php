<!DOCTYPE html>
<html>
    <head>
        <script src="/dist/main.min.js"></script>
        <title><?= PAGE ?></title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <?php include(__DIR__.'/styles.php'); ?>
        <header style='font-family: "Chela";' class="bg-neutral-900">
            <div class="mx-auto py-6 max-w-6xl flex justify-between gap-8">
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
        <main>
