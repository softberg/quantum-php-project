<div class="row">
    <div class="col s12 center-align white-text">
        <div class="logo-block">
            <?php echo partial('partials/logo') ?>
        </div>

        <h2><?php _t('common.signup'); ?></h2>

        <?php if (session()->has('error')): ?>
            <?php echo partial('partials/messages/error') ?>
        <?php endif; ?>

        <div class="card transparent-card teal">
            <div class="card-content">
                <form method="post" action="<?php echo base_url() . '/' . current_lang() ?>/signup">
                    <div class="form-container">
                        <div class="input-field">
                            <label class="auth-form-label"><?php _t('common.email'); ?></label>
                            <input type="text" name="email" autocomplete="off" value="<?php echo old('username') ?>" />
                        </div>
                        <div class="input-field">
                            <label class="auth-form-label"><?php _t('common.password'); ?></label>
                            <input type="password" name="password"  />
                        </div>
                        <div class="input-field">
                            <label class="auth-form-label"><?php _t('common.first_name'); ?></label>
                            <input type="text" name="firstname"  value="<?php echo old('firstname') ?>" />
                        </div>
                        <div class="input-field">
                            <label class="auth-form-label"><?php _t('common.last_name'); ?></label>
                            <input type="text" name="lastname"  value="<?php echo old('lastname') ?>" />
                        </div>
                        <div class="row">
                            <div class="col s12 right-align">
                                <a href="<?php echo base_url() . '/' . current_lang() ?>/signin" class="white-text"><?php _t('common.signin') ?></a>
                            </div>
                        </div>
                        <div>
                            <input type="hidden" name="token" value="<?php echo csrf_token() ?>" />
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
