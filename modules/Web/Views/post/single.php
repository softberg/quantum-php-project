<div class="back-btn">
    <a title="Back" href="<?php echo base_url() . '/' . current_lang() . '/posts'; ?>"><i class="material-icons">arrow_back</i></a>
</div>
<div class="center-align posts-container">
    <div class="teal-text">
        <h2>
            <span><?php echo $post['title'] ?></span>
        </h2>
        <p class="teal-text text-darken-4"><?php echo $post['content'] ?></p>
    </div>
</div>
