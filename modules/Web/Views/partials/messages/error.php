<div class="material-alert error">
    <?php $error = session()->getFlash('error') ?>
    <?php if ($error): ?>
        <?php if (is_array($error)): ?>
            <ul class="left-align">
                <?php foreach ($error as $field => $messages): ?> 
                    <?php foreach ($messages as $message): ?>
                        <li><?php echo $message ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <?php echo $error ?>
        <?php endif; ?>
    <?php endif; ?>
</div>