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
                                        <div class="heading-text el-text bottom-t-top animate_when_almost_visible" data-delay="200">
                                            <h2 class="font-762333 fontsize-155944 fontheight-179065 fontspace-781688">
                                                <span>Posts</span>
                                            </h2>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="heading-text el-text mobile-hidden bottom-t-top animate_when_almost_visible" data-delay="400">
                                            <ul class="step-list">
                                                <?php foreach ($posts as $id => $post): ?>
                                                    <li>
                                                        <h5>#<?php echo $id ?> <?php echo $post['title'] ?></h5>
                                                        <p><?php echo $post['content'] ?></p>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <div class="clear"></div>
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

