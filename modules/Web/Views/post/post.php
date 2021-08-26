<div class="posts-container">
    <h2 class="center-align teal-text"><?php _t('common.posts') ?></h2>
    <div class="row post_container">
        <?php if (count($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <?php echo partial('post/partials/post-item', ['post' => $post]) ?>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <h4 class="center-align"><?php _t('common.no_posts') ?>... <?php _t('common.try_creating') ?></h4>
    <?php endif; ?>

    <?php echo partial('post/partials/modal', ['item' => t('common.the_post')]) ?>

    <?php if (auth()->check() && auth()->user()->getFieldValue('role') == 'admin'): ?>
        <div class="fixed-action-btn">
            <a class="btn-floating btn-large waves-effect waves-light blue-grey darken-1 hoverable"
               href="<?php echo base_url() . '/' . current_lang() ?>/post/create"><i class="material-icons">add</i></a>
        </div>
    <?php endif; ?>
</div>
