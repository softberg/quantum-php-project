<div class="main-wrapper teal accent-4">
    <div class="container">
        <div class="row">
            <div class=" col s12 l8 offset-l2 center-align white-text">
                <h1><?php _t('common.signup'); ?></h1>

                <?php if (session()->has('error')) : ?>
                    <?php echo partial('partials/messages/error') ?>
                <?php endif; ?>

                <?php if (session()->has('success')): ?>
                    <?php echo partial('partials/messages/success') ?>
                <?php endif; ?>
                
                <div class="card teal accent-4">
                    <div class="card-content">
                        <form id="signUpForm" method="post" action="<?php echo base_url(true) . '/' . current_lang() ?>/signup">
                            <div class="row">
                                <div class="input-field col s12">
                                    <label class="auth-form-label"><?php _t('common.email'); ?></label>
                                    <input type="text" name="email" autocomplete="off" value="<?php echo old('username') ?>" />
                                </div>
                            </div>
                            <div class="row p-rel">
                                <div class="input-field col s12">
                                    <label class="auth-form-label"><?php _t('common.password'); ?></label>
                                    <input type="password" name="password" />
                                    <i class="material-icons visibility-icon on hide">visibility</i>
                                    <i class="material-icons visibility-icon off">visibility_off</i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <label class="auth-form-label"><?php _t('common.first_name'); ?></label>
                                    <input type="text" name="firstname" value="<?php echo old('firstname') ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <label class="auth-form-label"><?php _t('common.last_name'); ?></label>
                                    <input type="text" name="lastname" value="<?php echo old('lastname') ?>" />
                                </div>
                            </div>
                            <?php getRecaptcha(); ?>
                            <div class="row">
                                <div class="col s12 right-align">
                                    <a href="<?php echo base_url(true) . '/' . current_lang() ?>/signin" class="white-text"><?php _t('common.signin') ?></a>
                                </div>
                            </div>
                            <div class="row">
                                <?php echo htmlspecialchars_decode($captcha) ?>
                            </div>
                            <div>
                                <input type="hidden" name="csrf-token" value="<?php echo csrf_token() ?>" />
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
<?php echo htmlspecialchars_decode($captchaJs) ?>


<script src="https://www.google.com/recaptcha/enterprise.js?render=6LfkRXwjAAAAAAYX8Eb641ZcbdsUjjuFbJKAum4S"></script>
<script>
    grecaptcha.enterprise.ready(function() {
        grecaptcha.enterprise.execute('6LfkRXwjAAAAAAYX8Eb641ZcbdsUjjuFbJKAum4S', {action: 'login'}).then(function(token) {
        ...
        });
    });
</script>
