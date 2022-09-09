<li class="auth-lang-switcher">
    <a class="dropdown-trigger main-menu-link" href="#" data-target="<?php echo $attr ?>">
        <i class="material-icons dp48 left">language</i>
        <?php echo mb_substr(t('common.' . current_lang()), 0, 3); ?>
    </a>
    <ul id="<?php echo $attr ?>" class="dropdown-content">
        <?php if ($langs) : ?>
            <?php foreach ($langs as $lang) : ?>
                <li>
                    <a href="<?php echo url_with_lang($lang) ?>"><?php echo _t('common.' . $lang) ?></a>
                </li>
            <?php endforeach ?>
        <?php endif ?>
    </ul>
</li>