<div class="center-align white-text">
    <h2><?php _t('common.about'); ?></h2>

    <div class="card teal">
        <div class="card-content">
            <h6><?php _t('common.about_framework'); ?></h6>

            <h4><?php _t('common.version'); ?></h4>
            <h6><?php _t('common.current_version', env('APP_VERSION')); ?></h6>


            <h4><?php _t('common.installation'); ?></h4>
            <ul class="step-list">
                <li>
                    <h6><?php _t('common.create_project'); ?></h6>
                    <code>&gt; composer create-project quantum/project {project name}</code>
                </li>
                <li>
                    <h6><?php _t('common.run_server'); ?></h6>
                    <code>&gt; php -S localhost:8080 -t {project name}/public</code>
                </li>
            </ul>
        </div>
    </div>
</div>
