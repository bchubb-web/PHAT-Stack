<section class="flex align-center justify-center flex-col">
<?php if (count(PARAMS) > 0) {
    HTMX::component('stack');
    HTMX::component('stack-buttons');
} ?>
</section>
