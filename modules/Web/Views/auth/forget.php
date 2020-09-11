<div class="row">
    <div class="col s12 center-align white-text">
        <div class="logo-block">
            <?php echo partial('partials/logo') ?>
        </div>

        <h2><?php _t('common.forget_password'); ?></h2>

        <?php if (session()->has('error')): ?>
            <?php echo partial('partials/messages/error') ?>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <?php echo partial('partials/messages/success') ?>
        <?php endif; ?>

        <div class="card transparent-card teal">
            <div class="card-content">
                <form method="post" action="<?php echo base_url() . '/' . current_lang() ?>/forget">
                    <div class="input-field col s12">
                        <input type="text" name="email" id="email" />
                        <label for="email" class="white-text"><?php _t('common.email'); ?></label>
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
                </form>
            </div>
        </div>
    </div>
</div>