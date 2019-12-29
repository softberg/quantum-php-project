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
                                        <form method="post" action="<?php echo base_url() ?>/signin">
                                            <div class="form-container">
                                                <div class="heading-text el-text bottom-t-top animate_when_almost_visible" data-delay="200">
                                                    <h2 class="font-762333 fontsize-155944 fontheight-179065 fontspace-781688">
                                                        <span>Sign In</span>
                                                    </h2>
                                                </div>
                                                <?php if (session()->has('error')): ?>
                                                    <div class="heading-text el-text bottom-t-top animate_when_almost_visible" data-delay="300">
                                                        <div class="alert alert-danger" role="alert">
                                                            <?php echo session()->getFlash('error') ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="clear"></div>
                                                <div class="heading-text el-text mobile-hidden bottom-t-top animate_when_almost_visible pt-30" data-delay="400">
                                                    <label class="auth-form-label">Email</label>
                                                    <input type="text" name="email" class="form-control" placeholder="Email" />
                                                </div>
                                                <div class="heading-text el-text mobile-hidden bottom-t-top animate_when_almost_visible pt-30" data-delay="500">
                                                    <label class="auth-form-label">Password</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Password" />
                                                </div>
                                                <div class="heading-text el-text mobile-hidden bottom-t-top animate_when_almost_visible pt-30" data-delay="600">
                                                    <a href="<?php echo base_url() ?>/signup" class="pull-left">Sign Up</a>
                                                    <label class="auth-form-label pull-right">Remember Me
                                                        <input type="checkbox" name="remember" />
                                                    </label>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="heading-text el-text mobile-hidden bottom-t-top animate_when_almost_visible pt-30" data-delay="800">
                                                    <input type="hidden" name="token" value="<?php echo csrf_token() ?>" />
                                                    <input type="submit" value="Sign In" class="btn btn-success" />
                                                </div>
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
