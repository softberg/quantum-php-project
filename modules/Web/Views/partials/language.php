<ul class="right auth-lang-switcher">
    <li>
        <?php $lng = t('common.' . current_lang()); ?>
        <a class="dropdown-trigger" href="#" data-target="dropdown2" style="font-size:24px; color: #fff"><span class="material-icons dp48">language</span> <small><?php echo mb_substr($lng, 0, 3); ?></small></a>
        <ul id="dropdown2" class="dropdown-content">
            <?php 
                if($langs): 
                    foreach($langs as $lang): ?>
                        <li><a href="<?php echo base_url() . '/'  . preg_replace("/" . preg_quote("/" . current_lang(), '/') ."/", $lang, current_route_uri(), 1); ?>"><?php echo _t('common.' . $lang) ?></a></li>
                    <?php 
                    endforeach;
                endif 
            ?>
        </ul>
    </li>
</ul>