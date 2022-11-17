<div class="main-wrapper teal accent-4">
    <div class="container">
        <div class="row">
            <div class=" col s12 l8 offset-l2 center-align white-text">
                <h1><?php _t('common.forget_password'); ?></h1>

                <?php if (session()->has('error')): ?>
                    <?php echo partial('partials/messages/error') ?>
                <?php endif; ?>

                <?php if (session()->has('success')): ?>
                    <?php echo partial('partials/messages/success') ?>
                <?php endif; ?>

                <div class="card teal accent-4">
                    <div class="card-content">
                        <form method="post" action="<?php echo base_url(true) . '/' . current_lang() ?>/forget">
                            <div class="input-field col s12">
                                <input type="text" name="email" id="email"/>
                                <label for="email" class="white-text"><?php _t('common.email'); ?></label>
                            </div>
                            <div class="row">
                                <div class="col s12 right-align">
                                    <a href="<?php echo base_url(true) . '/' . current_lang() ?>/signin"
                                       class="white-text"><?php _t('common.signin') ?></a>
                                </div>
                            </div>
                            <div>
                                <input type="hidden" name="token" value="<?php echo csrf_token() ?>"/>
                                <button class="btn btn-large waves-effect waves-light" type="submit">
                                    <?php _t('common.send') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>