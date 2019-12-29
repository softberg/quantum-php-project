<div class="main-wrapper">
    <div class="main-container">
        <div class="page-wrapper main-onepage">
            <div class="sections-container">
                <div id="page-header">
                    <div class="header-wrapper header-uncode-block">
                        <div class="vc_row style-color-143431-bg row-container with-parallax onepage-section boomapps_vcrow">
                            <div class="pos-middle pos-left align_left align_center_tablet align_center_mobile column_child col-lg-6 boomapps_vccolumn col-md-100 single-internal-gutter">
                                <div class="uncol style-dark">
                                    <div class="uncont">
                                        <?php render_partial('partials/logo') ?>
                                        <div class="heading-text el-text bottom-t-top animate_when_almost_visible" data-delay="400">
                                            <h2 class="font-762333 fontsize-155944 fontheight-179065 fontspace-781688">
                                                <span>Quantum PHP Framework</span>
                                            </h2>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="heading-text el-text mobile-hidden bottom-t-top animate_when_almost_visible" data-delay="600">
                                            <h5 class="h4 fontheight-357766 font-weight-400">
                                                <span><?php _t('common.description') ?></span>
                                            </h5>
                                            <p>
                                                <a href="<?php echo base_url() . '/' . current_lang() ?>/about"><?php _t('common.about') ?></a>
                                                <?php if (!auth()->check()): ?>
                                                    <span class="v-seperator"></span>
                                                    <a href="<?php echo base_url() . '/' . current_lang() ?>/signin"><?php _t('common.signin') ?></a>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="empty-space empty-single"><span class="empty-space-inner"></span></div>
                                        <span class="btn-container btn-inline animate_when_almost_visible bottom-t-top" data-delay="800">
                                            <span style="font-size: 24px; font-family: Poppins!important;"><a href="https://quantum.softberg.org" target="_blank"><?php _t('common.learn_more') ?></a></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
