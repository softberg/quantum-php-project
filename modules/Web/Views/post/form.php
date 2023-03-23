<div class="post-form full-height">
    <div class="polaroid">
        <?php echo partial('post/partials/back') ?>
        <div class="row">
            <div class="col s12">
                <h1 class="center-align teal-text"><?php isset($post) ? _t('common.update_post') : _t('common.new_post') ?></h1>

                <?php if (session()->has('error')) : ?>
                    <?php echo partial('partials/messages/error') ?>
                <?php endif; ?>

                <?php echo partial('post/partials/modal', ['item' => t('common.the_image')]) ?>

                <div class="card teal accent-4">
                    <div class="card-content">
                        <form method="post" action="<?php echo base_url(true) . '/' . current_lang() . '/my-posts/' . (isset($post) ? 'amend/' . $post['id'] : 'create') ?>" enctype="multipart/form-data">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input value="<?php echo $post['title'] ?? old('title') ?>" name="title" id="title" type="text" class="validate">
                                    <label for="title"><?php _t('common.title') ?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <textarea name="content" id="content" data-length="1000" class="materialize-textarea"><?php echo $post['content'] ?? old('content') ?></textarea>
                                    <label for="content"><?php _t('common.content') ?></label>
                                </div>
                            </div>
                            <div class="file-field input-field upload-btn">
                                <div class="btn">
                                    <span>Image</span>
                                    <input type="file" name="image">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>

                            <div class="post-image">
                                <?php if (isset($post) && $post['image']) : ?>
                                    <a class="waves-effect waves-light btn modal-trigger image_delete"
                                    data-url="<?php echo base_url(true) . '/' . current_lang() . '/my-posts/delete-image/' . $post['id'] ?>"
                                    href="#modal-confirm"
                                    title="<?php _t('common.delete') ?>">
                                        <i class="material-icons">close</i>
                                    </a>
                                    <img src="<?php echo $post['image'] ?>" class="update_page_img">
                                <?php endif; ?>
                            </div>

                            <div class="center-align">
                                <input type="hidden" name="csrf-token" value="<?php echo csrf_token() ?>" />
                                <button class="btn btn-large waves-effect waves-light submit-btn" type="submit">
                                    <?php _t('common.save') ?>
                                </button>
                                <a href="<?php echo base_url(true) . '/' . current_lang() ?>/my-posts" class="btn btn-large waves-effect waves-teal btn-flat white-text">
                                    <?php _t('common.cancel') ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>