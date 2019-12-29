<ul class="navbar">
    <li><a class="<?php echo current_action() == 'index' ? 'active' : '' ?>" href="<?php echo base_url() ?>">Home</a></li>
    <li><a class="<?php echo current_action() == 'getPosts' || current_action() == 'getPost' ? 'active' : '' ?>" href="<?php echo base_url() ?>/posts">Posts</a></li>
    <?php if (auth()->user()->role == 'admin' || auth()->user()->role == 'editor'): ?>
        <li><a class="<?php echo current_action() == 'amendPost' ? 'active' : '' ?>" href="<?php echo base_url() ?>/post/amend">New Post</a></li>
    <?php endif; ?>
    <li style="float:right"><a href="<?php echo base_url() ?>/signout">Sign Out</a></li>
    <li style="float:right"><a href="javascript:;" class="static"><?php echo auth()->user()->firstname . ' ' . auth()->user()->lastname ?></a></li>
</ul>
