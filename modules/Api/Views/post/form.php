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
                                        <form method="post" action="<?php echo base_url() . '/post/amend/' . $id ?? '' ?>">
                                            <div class="heading-text el-text bottom-t-top animate_when_almost_visible pt-30" data-delay="200">
                                                <input class="form-control" name="title" value="<?php echo $post['title'] ?? '' ?>" placeholder="Title" />
                                            </div>
                                            <div class="heading-text el-text bottom-t-top animate_when_almost_visible pt-30" data-delay="300">
                                                <textarea class="form-control" name="content" style="height: 200px" placeholder="Content"><?php echo $post['content'] ?? '' ?></textarea>
                                            </div>
                                            <div class="heading-text el-text bottom-t-top animate_when_almost_visible pt-30" data-delay="400">
                                                <input type="hidden" name="token" value="<?php echo csrf_token() ?>" />
                                                <input type="submit" value="Save" class="btn btn-success" />
                                            </div>
                                        </form>
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
