<div class="posts-container">
    <h2 class="center-align teal-text"><?php _t('common.posts') ?></h2>
    <div class="row">
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $id => $post): ?>
                <div class="col s12 l6 post-item">
                    <div class="col s12">
                        <div class="card post-card hoverable">
                            <div class="card-content white teal-text text-darken-4">
                                <span class="card-title post-title">
                                    <a class="teal-text" href="<?php echo base_url() . '/' . current_lang() . '/post/' . $id ?>"><?php echo $post['title'] ?></a>
                                </span>
                                <p class="truncate"><?php echo $post['content'] ?></p>
                            </div>
                            <?php if (auth()->user()->role == 'admin'): ?>
                                <div class="card-action   teal accent-4 right-align">
                                    <span class="edit-post">
                                        <a href="<?php echo base_url() . '/' . current_lang() . '/post/amend/' . $id ?>" class="white-text" title="<?php _t('common.edit') ?>">
                                            <i class="material-icons dp48">edit</i>
                                        </a>
                                    </span>
                                    <span class="edit-post">
                                        <a href="<?php echo base_url() . '/' . current_lang() . '/post/delete/' . $id ?>" class="delete-post white-text" title="<?php _t('common.delete') ?>">
                                            <i class="material-icons dp48">delete</i>
                                        </a>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
    <h4 class="center-align"><?php _t('common.no_posts') ?>... <?php  _t('common.try_creating') ?></h4>
    <?php endif; ?>

    <?php if (auth()->user()->role == 'admin'): ?>
        <div class="fixed-action-btn">
            <a class="btn-floating btn-large waves-effect waves-light blue-grey darken-1 hoverable" href="<?php echo base_url() . '/' . current_lang() ?>/post/amend"><i class="material-icons">add</i></a>
        </div>
    <?php endif; ?>
</div>
