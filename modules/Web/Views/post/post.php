<div class="posts-container">
    <h2 class="center-align teal-text"><?php _t('common.posts') ?></h2>
    <div class="row post_container">
        <?php if (count($posts) > 0): ?>
        <?php foreach ($posts as $post): ?>
            <div class="col s12 m4 post-item">
                <div class="card post-card hoverable">
                    <a href="<?php echo base_url() . '/' . current_lang() . '/post/' . $post['id'] ?>">
                        <div class="card-image">
                            <?php if ($post['image']): ?>
                                <?php if(file_exists('uploads/'.$post['image'])): ?>
                                    <img src="<?php echo base_url() ?>/uploads/<?php echo $post['image'] ?>" class="content_img">
                                <?php else: ?>    
                                    <img src="<?php echo $post['image'] ?>" class="content_img">
                                <?php endif; ?>    
                            <?php else: ?>
                                <img src="<?php echo base_url() ?>/assets/images/no-image.png" class="content_no_img">
                            <?php endif; ?>    
                        </div>
                    </a>
                    <div class="card-content white teal-text text-darken-4">
                                <span class="card-title post-title">
                                    <a class="teal-text"
                                       href="<?php echo base_url() . '/' . current_lang() . '/post/' . $post['id'] ?>"><?php echo $post['title'] ?></a>
                                </span>
                        <p class="truncate"><?php echo $post['content'] ?></p>
                    </div>
                    <div class="card-action teal accent-4">
                        <div class="row">
                            <div class="col s8">
                                <div class="post-date"><?php echo $post['updated_at'] ?? '' ?></div>
                                <div class="post-author"><?php echo $post['author'] ?? '' ?></div>
                            </div>
                            <div class="col s4 right-align">
                                <?php if (auth()->user()->getFieldValue('role') == 'admin'): ?>
                                    <span class="edit-post">
                                        <a href="<?php echo base_url() . '/' . current_lang() . '/post/amend/' . $post['id'] ?>"
                                           class="white-text" title="<?php _t('common.edit') ?>">
                                            <i class="material-icons dp48">edit</i>
                                        </a>
                                    </span>
                                    <span class="edit-post">
                                        <a href="<?php echo base_url() . '/' . current_lang() . '/post/delete/' . $post['id'] ?>"
                                           class="delete-post white-text" title="<?php _t('common.delete') ?>">
                                            <i class="material-icons dp48">delete</i>
                                        </a>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <h4 class="center-align"><?php _t('common.no_posts') ?>... <?php _t('common.try_creating') ?></h4>
    <?php endif; ?>

    <?php if (auth()->user()->getFieldValue('role') == 'admin'): ?>
        <div class="fixed-action-btn">
            <a class="btn-floating btn-large waves-effect waves-light blue-grey darken-1 hoverable"
               href="<?php echo base_url() . '/' . current_lang() ?>/post/amend"><i class="material-icons">add</i></a>
        </div>
    <?php endif; ?>
</div>
