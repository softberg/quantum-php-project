<div class="posts-container">
    <h2 class="center-align teal-text"><?php _t('common.my_posts') ?></h2>
    <div class="row post_container">
        <?php if (count($posts)): ?>
        <?php foreach ($posts as $post): ?>

            <ul class="collection">
                <li class="collection-item avatar">
                    <?php if ($post['image']): ?>
                        <img src="<?php echo $post['image'] ?>" class="circle img-my-post">
                    <?php else: ?>
                        <img src="<?php echo base_url() ?>/assets/images/no-image.png" class="circle img-my-post">
                    <?php endif; ?>
                    <span class="title post-title" title="<?php echo $post['title'] ?>">
                        <a class="teal-text post-title"
                           href="<?php echo base_url() . '/' . current_lang() . '/posts/' . $post['uuid'] ?>">
                            <?php echo $post['title'] ?>
                        </a>
                    </span>
                    <p>
                        <?php echo date('m/d/Y H:i', strtotime($post['updated_at'])) ?? '' ?> <br>
                        <?php echo $post['author'] ?? '' ?>
                    </p>
                    <a href="<?php echo base_url() . '/' . current_lang() . '/my-posts/amend/' . $post['uuid'] ?>"
                       class="secondary-content edit-my-post" title="<?php _t('common.edit') ?>" style="right: 50px;">
                        <i class="material-icons dp48">edit</i>
                    </a>
                    <a href="#modal-confirm" class="secondary-content modal-trigger"
                       data-url="<?php echo base_url() . '/' . current_lang() ?>/my-posts/delete/<?php echo $post['uuid'] ?>">
                        <i class="material-icons dp48">delete</i>
                    </a>
                </li>

            </ul>

        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <h4 class="center-align"><?php _t('common.no_posts') ?>... <?php _t('common.try_creating') ?></h4>
    <?php endif; ?>

    <?php echo partial('post/partials/modal', ['item' => t('common.the_post')]) ?>

    <?php if (auth()->check()): ?>
        <div class="fixed-action-btn">
            <a class="btn-floating btn-large waves-effect waves-light blue-grey darken-1 hoverable"
               href="<?php echo base_url() . '/' . current_lang() ?>/my-posts/create"><i class="material-icons">add</i></a>
        </div>
    <?php endif; ?>
</div>