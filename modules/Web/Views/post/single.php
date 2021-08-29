<div class="center-align posts-container">
    <div class="polaroid">
        <?php echo partial('post/partials/back') ?>
        <h1 class="single-blog-title"><?php echo $post['title'] ?></h1>
        <div class="row">
            <div class="col s12 center-align post-date teal-text text-accent-4"><?php echo $post['updated_at'] ?? '' ?></div>
            <div class="col s12 center-align post-author teal-text text-accent-4"><?php echo $post['author'] ?? '' ?></div>
        </div>
        <?php if ($post['image']): ?>
            <img src="<?php echo $post['image'] ?>" class="single_page_img">
        <?php endif; ?>

        <p class="left-align single-blog-txt"><?php echo nl2br($post['content']) ?></p>
    </div>
</div>
