<nav>
    <div class="nav-wrapper teal accent-4 row">
        <span class="navbar-logo">
            <?php echo partial('partials/logo') ?>
        </span>

        <ul class="right">
            <li>
                <a class="dropdown-trigger" href="#!" data-target="dropdown1">
                    <?php echo auth()->user()->firstname . ' ' . auth()->user()->lastname ?> 
                    <i class="material-icons right">arrow_drop_down</i>
                </a>
                <ul id="dropdown1" class="dropdown-content">
                    <li>
                        <a href="<?php echo base_url() . '/' . current_lang() ?>/signout"><?php _t('common.signout'); ?></a>
                    </li>
                </ul>
            </li>
        </ul>

        <?php echo partial('partials/language') ?>
    </div>
</nav>