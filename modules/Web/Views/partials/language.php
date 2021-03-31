<ul class="right auth-lang-switcher">
    <li>
        <a class="dropdown-trigger" href="#" data-target="dropdown2" style="font-size:24px; color: #fff">
            <span class="material-icons dp48">language</span> 
            <small><?php echo mb_substr(t('common.' . current_lang()), 0, 3); ?></small>
        </a>
        <ul id="dropdown2" class="dropdown-content">
            <?php
            if ($langs):
                foreach ($langs as $lang):
                    ?>
                    <li>
                        <a href="<?php echo url_with_lang($lang) ?>"><?php echo _t('common.' . $lang) ?></a>
                    </li>
                    <?php
                endforeach;
            endif
            ?>
        </ul>
    </li>
</ul>