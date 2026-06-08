<?php $__env->startSection('auth'); ?>


    <?php if(\Request::is('static-sign-up')): ?> 
        <?php echo $__env->make('layouts.navbars.guest.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->yieldContent('content'); ?>
        <?php echo $__env->make('layouts.footers.guest.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php elseif(\Request::is('static-sign-in')): ?> 
        <?php echo $__env->make('layouts.navbars.guest.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->yieldContent('content'); ?>
        <?php echo $__env->make('layouts.footers.guest.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php else: ?>
        <?php if(\Request::is('rtl')): ?>  
            <?php echo $__env->make('layouts.navbars.auth.sidebar-rtl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <main class="main-content position-relative mt-1 border-radius-lg">
                <?php echo $__env->make('layouts.navbars.auth.nav-rtl', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="container-fluid py-4">
                    <?php echo $__env->yieldContent('content'); ?>
                    <?php echo $__env->make('layouts.footers.auth.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </main>

        <?php elseif(\Request::is('profile')): ?>  
            <?php echo $__env->make('layouts.navbars.auth.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="main-content position-relative bg-gray-100">
                <?php echo $__env->make('layouts.navbars.auth.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>

        <?php elseif(\Request::is('virtual-reality')): ?> 
            <?php echo $__env->make('layouts.navbars.auth.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="border-radius-xl mt-3 mx-3 position-relative" style="background-image: url('../assets/img/vr-bg.jpg') ; background-size: cover;">
                <?php echo $__env->make('layouts.navbars.auth.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <main class="main-content mt-1 border-radius-lg">
                    <?php echo $__env->yieldContent('content'); ?>
                </main>
            </div>
            <?php echo $__env->make('layouts.footers.auth.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php else: ?>
            <?php if(Auth::guard('student')->check()): ?>
                <?php echo $__env->make('layouts.navbars.auth.student-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?>
                <?php echo $__env->make('layouts.navbars.auth.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
            <main class="main-content position-relative mt-1 border-radius-lg">
                <?php if(Auth::guard('student')->check()): ?>
                    <?php echo $__env->make('layouts.navbars.auth.student-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php else: ?>
                    <?php echo $__env->make('layouts.navbars.auth.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
                <div class="container-fluid py-4">
                    <?php echo $__env->yieldContent('content'); ?>
                    <?php echo $__env->make('layouts.footers.auth.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </main>
        <?php endif; ?>

        
    <?php endif; ?>

    

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\YCcenter\resources\views/layouts/user_type/auth.blade.php ENDPATH**/ ?>