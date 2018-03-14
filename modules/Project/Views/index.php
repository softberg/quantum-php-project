<ul class="tab-group">
    <li class="tab active"><a href="#signup">Sign Up</a></li>
    <li class="tab"><a href="#login">Log In</a></li>
</ul>

<div class="tab-content">
    <div id="signup">   
        <h1>Sign Up for Free</h1>

        <form action="<?php echo base_url() ?>/auth/signup" method="post">

            <div class="top-row">
                <div class="field-wrap">
                    <label>
                        First Name<span class="req">*</span>
                    </label>
                    <input type="text" name="firstname" required autocomplete="off" />
                </div>

                <div class="field-wrap">
                    <label>
                        Last Name<span class="req">*</span>
                    </label>
                    <input type="text"required name="lastname" autocomplete="off"/>
                </div>
            </div>

            <div class="field-wrap">
                <label>
                    Email Address<span class="req">*</span>
                </label>
                <input type="email" name="email" required autocomplete="off"/>
            </div>

            <div class="field-wrap">
                <label>
                    Set A Password<span class="req">*</span>
                </label>
                <input type="password" name="pass" required autocomplete="off"/>
            </div>

            <button type="submit" class="button button-block"/>Get Started</button>

        </form>

    </div>

    <div id="login">   
        <h1>Welcome Back!</h1>

        <form action="<?php echo base_url() ?>/auth/signin" method="post">

            <div class="field-wrap">
                <label>
                    Email Address<span class="req">*</span>
                </label>
                <input type="email"required name="email" autocomplete="off"/>
            </div>

            <div class="field-wrap">
                <label>
                    Password<span class="req">*</span>
                </label>
                <input type="password" name="pass" required autocomplete="off"/>
            </div>

            <p class="forgot"><a href="#">Forgot Password?</a></p>

            <button class="button button-block"/>Log In</button>

        </form>

    </div>

</div>