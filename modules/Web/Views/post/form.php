<div class="back-btn">
    <a title="Back" href="<?php echo base_url() . '/' . current_lang() . '/posts'; ?>"><i class="material-icons">arrow_back</i></a>
</div>
<div class="post-form">
    <div class="row">
        <div class="col s12 l8 offset-l2">

            <h2 class="center-align teal-text"><?php isset($id) ? _t('common.update_post') : _t('common.new_post') ?></h2>

            <?php if (session()->has('error')): ?>
                <?php echo partial('partials/messages/error') ?>
            <?php endif; ?>

            <div class="card teal accent-4">
                <div class="card-content">
                    <form method="post" action="<?php echo base_url() . '/' . current_lang() . '/post/amend/' . ($id ?? '') ?>" enctype="multipart/form-data">
                        <div class="row">
                            <div class="input-field col s12">
                                <input value="<?php echo $post['title'] ?? old('title') ?>" name="title" id="title" type="text" class="validate">
                                <label for="title"><?php _t('common.title') ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea name="content" id="content" class="materialize-textarea"><?php echo $post['content'] ?? old('content') ?></textarea>
                                <label for="content"><?php _t('common.content') ?></label>
                            </div>
                        </div>
                        <div class="file-field input-field">
                            <div class="btn">
                                <span>Image</span>
                                <input type="file" name="image">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" value="<?php echo $post['image'] ?? old('image')?>" type="text">
                            </div>
                        </div>
                        <div class="row">
                        <?php if(isset($id)): ?>   
                            <?php if ($post['image']): ?>
                                <img src="<?php echo base_url() ?>/uploads/<?php echo $post['image'] ?>" class="update_page_img">
                            <?php else: ?>   
                                <div class="update_page_img"><img src="<?php echo base_url() ?>/assets/images/no-image.png" class="post_image"></div>
                            <?php endif; ?>
                        <?php endif; ?>       
                        </div>
                        <div class="center-align">
                            <input type="hidden" name="token" value="<?php echo csrf_token() ?>" />
                            <button class="btn btn-large waves-effect waves-light blue-grey" type="submit">
                                <?php _t('common.save') ?>
                            </button>
                            <a href="<?php echo base_url() . '/' . current_lang() ?>/posts" class="btn btn-large waves-effect waves-teal btn-flat white-text">
                                <?php _t('common.cancel') ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
