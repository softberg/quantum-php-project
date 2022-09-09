<ul class="sidenav" id="mobile-demo">
    <li>
        <a href="<?php echo base_url() . '/' . current_lang() ?>">
            <i class="material-icons left">home</i>
            <?php _t('common.home') ?>
        </a>
    </li>
    <li>
        <a href="<?php echo base_url() . '/' . current_lang() ?>/posts">
            <i class="material-icons left">assignment</i>
            <?php _t('common.posts') ?>
        </a>
    </li>
    <?php if (auth()->check()) : ?>
        <li>
            <a class="dropdown-trigger login-list" href="#!" data-target="sidenav-dropdown1">
                <i class="material-icons left">person</i>
                <?php echo auth()->user()->getFieldValue('firstname') . ' ' . auth()->user()->getFieldValue('lastname') ?>
                <i class="material-icons right">arrow_drop_down</i>
            </a>
            <ul id="sidenav-dropdown1" class="dropdown-content">
                <li>
                    <a href="<?php echo base_url() . '/' . current_lang() ?>/my-posts">
                        <?php _t('common.my_posts') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url() . '/' . current_lang() ?>/signout"><?php _t('common.signout'); ?></a>
                </li>
            </ul>
        </li>
    <?php else : ?>
        <li>
            <?php if (route_name() != 'signup') : ?>
                <a href="<?php echo base_url() . '/' . current_lang() ?>/signup">
                    <i class="material-icons left">person_add</i>
                    <?php _t('common.signup') ?>
                </a>
            <?php endif; ?>
        </li>
        <li>
            <?php if (route_name() != 'signin') : ?>
                <a href="<?php echo base_url() . '/' . current_lang() ?>/signin">
                    <i class="material-icons left">exit_to_app</i>
                    <?php _t('common.signin') ?>
                </a>
            <?php endif; ?>
        </li>
    <?php endif; ?>
    <?php echo partial('partials/language', ['attr' => 'sidenav-dropdown2']) ?>

</ul>