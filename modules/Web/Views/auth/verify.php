<div class="row">
    <div class="col s12 center-align white-text">
        <div class="logo-block">
            <?php  echo partial('partials/logo') ?>
        </div>

        <h2><?php _t('common.2sv'); ?></h2>

        <?php if (session()->has('error')): ?>
            <?php echo partial('partials/messages/error') ?>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <?php echo partial('partials/messages/success') ?>
        <?php endif; ?>

        <div class="card transparent-card teal">
            <div class="card-content">
                <form method="post" action="<?php echo base_url() . '/' . current_lang() . '/verify' ?>">
                    <div class="form-container">
                        <div class="input-field">
                            <label class="auth-form-label"><?php _t('common.otp'); ?></label>
                            <input type="text" name="otp" />
                            <input type="hidden" name="hash" value="<?php echo $hash; ?>"/>
                            <a style="color: white; font-size: 14px" href="<?php echo base_url() . '/' . current_lang() . '/resend/' . $hash ?>">
                                <?php _t('common.resend_otp') ?>
                            </a>
                        </div>

                        <div>

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