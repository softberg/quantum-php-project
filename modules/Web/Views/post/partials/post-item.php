<div class="col s12 m3 post-item">
    <div class="card post-card hoverable">
        <a href="<?php echo base_url(true) . '/' . current_lang() . '/post/' . $post['id'] ?>">
            <div class="card-image card-image-box">
                <?php if ($post['image']) : ?>
                    <img src="<?php echo base_url() . '/uploads/' . $post['image'] ?>" class="content_img">
                <?php else : ?>
                    <img src="<?php echo base_url() ?>/assets/images/no-image.png" class="content_no_img">
                <?php endif; ?>
            </div>
        </a>
        <div class="card-content white teal-text text-darken-4">
            <span class="card-title post-title" title="<?php echo $post['title'] ?>">
                <a class="teal-text" href="<?php echo base_url(true) . '/' . current_lang() . '/post/' . $post['id'] ?>">
                    <?php echo $post['title'] ?>
                </a>
            </span>
            <p class="truncate"><?php echo $post['content'] ?></p>
        </div>
        <div class="card-action teal accent-4">
            <div class="row">
                <div class="col s12">
                    <div class="post-date"><?php echo $post['date'] ?></div>
                    <div class="post-author"><?php echo $post['author'] ?></div>
                </div>
            </div>
        </div>
    </div>
</div>