<div class="main-wrapper posts-container">
    <h1 class="center-align teal-text"><?php _t('common.my_posts') ?></h1>
    <div class="row container">
        <?php if (count($posts)): ?>
            <ul class="collection">
                <?php foreach ($posts as $post): ?>
                    <?php echo partial('post/partials/my-post-item', ['post' => $post]) ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <h4 class="center-align grey-text"><?php _t('common.no_posts') ?>... <?php _t('common.try_creating') ?></h4>
    <?php endif; ?>

    <?php echo partial('post/partials/modal', ['item' => t('common.the_post')]) ?>

    <?php if (auth()->check()): ?>
        <div class="fixed-action-btn">
            <a class="btn-floating btn-large waves-effect waves-light blue-grey darken-1 hoverable"
               href="<?php echo base_url() . '/' . current_lang() ?>/my-posts/create"><i class="material-icons">add</i></a>
        </div>
    <?php endif; ?>
</div>