<div class="main-wrapper teal accent-4">
    <div class="container">
        <div class="row">
            <div class=" col s12 l8 offset-l2 center-align white-text">
                <h1><?php _t('common.2fa'); ?></h1>

                <?php if (session()->has('error')): ?>
                    <?php echo partial('partials/messages/error') ?>
                <?php endif; ?>

                <?php if (session()->has('success')): ?>
                    <?php echo partial('partials/messages/success') ?>
                <?php endif; ?>

                <div class="card teal accent-4">
                    <div class="card-content">
                        <form method="post" action="<?php echo base_url(true) . '/' . current_lang() . '/verify' ?>">
                            <div class="form-container">
                                <div class="input-field">
                                    <label class="auth-form-label"><?php _t('common.otp'); ?></label>
                                    <input type="text" name="otp"/>
                                    <input type="hidden" name="code" value="<?php echo $code; ?>"/>
                                    <a style="color: white; font-size: 14px"
                                       href="<?php echo base_url(true) . '/' . current_lang() . '/resend/' . $code ?>">
                                           <?php _t('common.resend_otp') ?>
                                    </a>
                                </div>

                                <div>

                                </div>
                                <div>
                                    <input type="hidden" name="csrf-token" value="<?php echo csrf_token() ?>"/>
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