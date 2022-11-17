<div class="main-wrapper teal accent-4">
    <div class="container wrapper">
        <div class="center-align white-text">
            <div class="logo-block">
                <?php echo partial('partials/logo') ?>
            </div>
            <h1><?php echo env('APP_NAME') ?></h1>
            <div class="card teal accent-4">
                <div class="card-content">
                    <h5><?php _t('common.description') ?></h5>
                </div>
            </div>
            <div class="index-links">
                <a href="<?php echo base_url(true) . '/' . current_lang() ?>/about" class="white-text"><?php _t('common.about') ?></a>
                <a href="https://quantum.softberg.org" target="_blank" class="white-text"><?php _t('common.learn_more') ?></a>
            </div>
        </div>
    </div>
</div>
<?php echo partial('partials/bubbles') ?>