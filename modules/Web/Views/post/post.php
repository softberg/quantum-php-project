<div class="posts-container">
    <h2 class="center-align teal-text"><?php _t('common.posts') ?></h2>
    <div class="row post_container">
        <?php if (count($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <?php echo partial('post/partials/post-item', ['post' => $post]) ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php if (empty($posts)): ?>
        <h4 class="center-align"><?php _t('common.no_posts') ?>... <?php _t('common.try_creating') ?></h4>
    <?php endif; ?>

    <?php echo partial('post/partials/modal', ['item' => t('common.the_post')]) ?>
</div>
