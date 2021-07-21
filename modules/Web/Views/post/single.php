<div class="back-btn">
    <a title="Back" href="<?php echo base_url() . '/' . current_lang() . '/posts'; ?>"><i class="material-icons">arrow_back</i></a>
</div>
<div class="center-align posts-container">
    <div class="polaroid">
        <h2>
            <span><?php echo $post['title'] ?></span>
        </h2>
        
        <?php if ($post['image']): ?>
        <img src="<?php echo base_url() ?>/uploads/<?php echo $post['image'] ?>" class="single_page_img">
        <?php else: ?>
            <img src="<?php echo base_url() ?>/assets/images/no-image.png" class="single_page_no_img">
        <?php endif; ?> 
        <p class="teal-text text-darken-4"><?php echo $post['content'] ?></p> 
    </div>
</div>
