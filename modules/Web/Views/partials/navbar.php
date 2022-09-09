<nav class="teal accent-4">
    <div class="nav-wrapper">
        <span class="navbar-logo brand-logo">
            <?php if (route_name() != 'home') : ?>
                <?php echo partial('partials/logo') ?>
            <?php endif; ?>
        </span>
        <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
            <li>
                <a href="<?php echo base_url() . '/' . current_lang() ?>/posts" class="white-text">
                    <?php _t('common.posts') ?>
                </a>
            </li>
            <?php if (auth()->check()) : ?>
                <li>
                    <a class="dropdown-trigger login-list" href="#!" data-target="dropdown1">
                        <span class="hide-on-small-only show-on-medium-and-up">
                            <i class="material-icons left">person</i>
                            <?php echo auth()->user()->getFieldValue('firstname') . ' ' . auth()->user()->getFieldValue('lastname') ?>
                            <i class="material-icons right">arrow_drop_down</i>
                        </span>
                    </a>
                    <ul id="dropdown1" class="dropdown-content">
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
                        <a href="<?php echo base_url() . '/' . current_lang() ?>/signup" class="white-text">
                            <?php _t('common.signup') ?>
                        </a>
                    <?php endif; ?>
                </li>
                <li>
                    <?php if (route_name() != 'signin') : ?>
                        <a href="<?php echo base_url() . '/' . current_lang() ?>/signin" class="white-text">
                            <?php _t('common.signin') ?>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endif; ?>
            <?php echo partial('partials/language') ?>
        </ul>
        
    </div>
</nav>
<?php echo partial('partials/sidebar') ?>