<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Інтернет-магазин')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans text-ui-fg antialiased bg-ui-bg">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-ui-bg">

            <div class="w-full sm:max-w-md mt-2 px-6 py-6 bg-ui-panel shadow-xl overflow-hidden sm:rounded-xl border border-ui-border/40">
                <?php echo e($slot); ?>

            </div>
            
            <div class="mt-6 text-center text-sm text-ui-muted">
                <a href="<?php echo e(route('catalog.index')); ?>" class="hover:text-ui-accent transition">
                    <i class="fas fa-arrow-left mr-1"></i>Повернутися до каталогу
                </a>
            </div>
        </div>
    </body>
</html>
<?php /**PATH /var/www/html/resources/views/layouts/guest.blade.php ENDPATH**/ ?>