<div class="main-wrapper teal accent-4">
    <div class="container">
        <div class="row">
            <div class=" col s12 l8 offset-l2 center-align white-text">
                <h1><?php _t('common.signin') ?></h1>

                <?php if (session()->has('error')): ?>
                    <?php echo partial('partials/messages/error') ?>
                <?php endif; ?>

                <div class="card teal accent-4">
                    <div class="card-content">
                        <form method="post" class="signup-form" action="<?php echo base_url(true) . '/' . current_lang() ?>/signin">
                            <div class="row">
                                <div class="input-field col s12">
                                    <label for="email"><?php _t('common.email') ?></label>
                                    <input name="email" id='email' type="text"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <label for="password" class="active"><?php _t('common.password') ?></label>
                                    <input name="password" id='password' type="password"/>
                                    <i class="material-icons visibility-icon on hide">visibility</i>
                                    <i class="material-icons visibility-icon off">visibility_off</i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 l6">
                                    <div class="row">
                                        <div class="col s12 left-align">
                                            <a href="<?php echo base_url(true) . '/' . current_lang() ?>/signup"
                                               class="white-text"><?php _t('common.signup') ?></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12 left-align">
                                            <a href="<?php echo base_url(true) . '/' . current_lang() ?>/forget"
                                               class="white-text"><?php _t('common.forget_password') ?></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col s12 l6 remember-box">
                                    <label>
                                        <input type="checkbox" name="remember" class="checkbox-white"/>
                                        <span class="white-text"><?php _t('common.remember_me') ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="csrf-token" value="<?php echo csrf_token() ?>"/>
                                <button class="btn btn-large waves-effect waves-light" type="submit">
                                    <?php _t('common.signin') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
