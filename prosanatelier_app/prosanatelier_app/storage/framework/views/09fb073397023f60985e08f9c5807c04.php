<?php if($paginator->hasPages()): ?>
    <nav class="prosan-pagination" role="navigation" aria-label="Pagination Navigation">
        <div class="prosan-pagination-info">
            <?php if(method_exists($paginator, 'firstItem') && $paginator->firstItem()): ?>
                Showing <?php echo e($paginator->firstItem()); ?> to <?php echo e($paginator->lastItem()); ?> of <?php echo e($paginator->total()); ?> results
            <?php endif; ?>
        </div>

        <ul class="prosan-pagination-list">
            
            <?php if($paginator->onFirstPage()): ?>
                <li><span class="prosan-page-link is-disabled" aria-disabled="true">&lsaquo; Previous</span></li>
            <?php else: ?>
                <li><a class="prosan-page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">&lsaquo; Previous</a></li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_string($element)): ?>
                    <li><span class="prosan-page-link is-disabled"><?php echo e($element); ?></span></li>
                <?php endif; ?>

                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li><span class="prosan-page-link is-active" aria-current="page"><?php echo e($page); ?></span></li>
                        <?php else: ?>
                            <li><a class="prosan-page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li><a class="prosan-page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">Next &rsaquo;</a></li>
            <?php else: ?>
                <li><span class="prosan-page-link is-disabled" aria-disabled="true">Next &rsaquo;</span></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH /home/niyamulp/prosanatelier_app/resources/views/vendor/pagination/prosan.blade.php ENDPATH**/ ?>