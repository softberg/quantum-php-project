<div class="col s12 m4 post-item">
    <div class="card post-card hoverable">
        <a href="<?php echo base_url() . '/' . current_lang() . '/post/' . $post['id'] ?>">
            <div class="card-image card-image-box">
                <?php if ($post['image']): ?>
                    <img src="<?php echo $post['image'] ?>" class="content_img">
                <?php else: ?>
                    <img src="<?php echo base_url() ?>/assets/images/no-image.png" class="content_no_img">
                <?php endif; ?>
            </div>
        </a>
        <div class="card-content white teal-text text-darken-4">
            <span class="card-title post-title" title="<?php echo $post['title'] ?>">
                <a class="teal-text"
                   href="<?php echo base_url() . '/' . current_lang() . '/post/' . $post['id'] ?>">
                    <?php echo $post['title'] ?>
                </a>
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
                    <?php if (auth()->check() && auth()->user()->getFieldValue('role') == 'admin'): ?>
                        <a href="<?php echo base_url() . '/' . current_lang() . '/post/amend/' . $post['id'] ?>"
                           class="white-text" title="<?php _t('common.edit') ?>">
                            <i class="material-icons dp48">edit</i>
                        </a>
                        <a href="#modal-confirm" class="modal-trigger white-text"
                           data-url="<?php echo base_url() . '/' . current_lang() ?>/post/delete/<?php echo $post['id'] ?>">
                            <i class="material-icons dp48">delete</i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>