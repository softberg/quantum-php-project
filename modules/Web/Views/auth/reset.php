<div class="main-wrapper teal accent-4">
    <div class="container">
        <div class="row">
            <div class=" col s12 l8 offset-l2 center-align white-text">
                <h1><?php _t('common.reset_password'); ?></h1>

                <?php if (session()->has('error')): ?>
                    <?php echo partial('partials/messages/error') ?>
                <?php endif; ?>

                <?php if (session()->has('success')): ?>
                    <?php echo partial('partials/messages/success') ?>
                <?php endif; ?>

                <div class="card transparent-card teal">
                    <div class="card-content">
                        <form method="post" action="<?php echo base_url() . '/' . current_lang() . '/reset/' . $reset_token ?>">
                            <div class="form-container">
                                <div class="input-field">
                                    <label class="auth-form-label"><?php _t('common.new_password'); ?></label>
                                    <input type="password" name="password"/>
                                    <i class="material-icons visibility-icon on hide">visibility</i>
                                    <i class="material-icons visibility-icon off">visibility_off</i>
                                </div>
                                <div class="input-field">
                                    <label class="auth-form-label"><?php _t('common.repeat_password'); ?></label>
                                    <input type="password" name="repeat_password"/>
                                    <i class="material-icons visibility-icon on hide">visibility</i>
                                    <i class="material-icons visibility-icon off">visibility_off</i>
                                </div>
                                <div>
                                    <input type="hidden" name="token" value="<?php echo csrf_token() ?>"/>
                                    <button class="btn btn-large waves-effect waves-light" type="submit">
                                        <?php _t('common.send') ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>