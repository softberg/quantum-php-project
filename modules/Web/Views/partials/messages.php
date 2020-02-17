<div class="heading-text el-text bottom-t-top animate_when_almost_visible" data-delay="300">
    <div class="alert alert-danger" role="alert">
        <?php $errors = session()->getFlash('error') ?>
        <?php if ($errors): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>