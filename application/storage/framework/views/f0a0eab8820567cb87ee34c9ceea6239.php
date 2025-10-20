<div class="d-flex flex-wrap justify-content-end align-items-center">
    <form method="<?php echo e($method); ?>" class="form-inline" <?php if($action): ?> action="<?php echo e($action); ?>" <?php endif; ?> autocomplete="off">
        <div class="input-group justify-content-end">
            <input type="text" name="<?php echo e($name); ?>" class="form-control bg--white search-color" 
                   placeholder="<?php echo e($placeholder); ?>" value="<?php echo e($value); ?>">
            <button class="btn btn--primary input-group-text" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </form>
</div><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/components/search-form.blade.php ENDPATH**/ ?>