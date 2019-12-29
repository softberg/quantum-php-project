<ul class="navbar">
    <li>
        <a class="<?php echo current_action() == 'index' ? 'active' : '' ?>"
           href="<?php echo base_url() . '/' . current_lang() ?>"><?php _t('common.home'); ?></a>
    </li>
    <li>
        <a class="<?php echo current_action() == 'getPosts' || current_action() == 'getPost' ? 'active' : '' ?>"
           href="<?php echo base_url() . '/' . current_lang() ?>/posts"><?php _t('common.posts'); ?></a>
    </li>
    <?php if (auth()->user()->role == 'admin' || auth()->user()->role == 'editor'): ?>
        <li>
            <a class="<?php echo current_action() == 'amendPost' ? 'active' : '' ?>"
               href="<?php echo base_url() . '/' . current_lang() ?>/post/amend"><?php _t('common.new_post'); ?></a>
        </li>
    <?php endif; ?>
    <li style="float:right">
        <a href="<?php echo base_url() . '/' . current_lang() ?>/signout"><?php _t('common.signout'); ?></a>
    </li>
    <li style="float:right">
        <a href="javascript:;"
           class="static"><?php echo auth()->user()->firstname . ' ' . auth()->user()->lastname ?></a>
    </li>
</ul>
