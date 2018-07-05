<!-- Register form -->
<div id="signup-bloc">
    <div id="signup-title">
        <h3><?= $langFile->header_registerform_part1; ?><span class="orange"><?= $langFile->header_registerform_part2; ?></span> <?= $langFile->header_registerform_part3; ?></h3>
        <h1><?= $langFile->title_form; ?></h1>
    </div>

    <div class="messages-container col-md-12">
        <div class="error-input-container error-field error-nickname novisible" tabindex="0">
            <p><?= $langFile->nickname_fail; ?></p>
        </div>
        <div class="error-input-container error-field error-firstname novisible" tabindex="0">
            <p><?= $langFile->firstname_fail; ?></p>
        </div>
        <div class="error-input-container error-field error-lastname novisible" tabindex="0">
            <p><?= $langFile->lastname_fail; ?></p>
        </div>
        <div class="error-input-container error-field error-email-invalid novisible" tabindex="0">
            <p><?= $langFile->email_fail; ?></p>
        </div>
        <div class="error-input-container error-field error-email-inuse novisible" tabindex="0">
            <p><?= $langFile->email_inuse; ?></p>
        </div>
        <div class="error-input-container error-field error-password-invalid novisible" tabindex="0">
            <p><?= $langFile->password_fail; ?></p>
        </div>
        <div class="error-input-container error-field error-password-size novisible" tabindex="0">
            <p>Password must have 6 caracters minimum</p>
        </div>
        <div class="error-input-container error-field error-password-match novisible" tabindex="0">
            <p><?= $langFile->password_match_fail; ?></p>
        </div>
        <div class="error-input-container error-field error-cdu novisible" tabindex="0">
            <p><?= $langFile->condition_accept_fail; ?></p>
        </div>
        <div class="valid-input-container error-field valid-account novisible" tabindex="0">
            <p><?= $langFile->register_achieved; ?></p>
        </div>
    </div>

    <div class="form" id="signup-form">
        <div class="loader-register loader-container">
            <span class="loader loader-double">
            </span>
        </div>
        <form action='' method='post'>
            <div class="row">
                <div class="col-md-4">
                    <input type='text' name='fname-signup' placeholder="<?= $langFile->placeholder_firstname; ?>"/>
                </div>
                <div class="col-md-4">
                    <input type='text' name='nickname-signup' placeholder="<?= $langFile->placeholder_nickname; ?>"/>
                </div>
                <div class="col-md-4">
                    <input type='text' name='lname-signup' placeholder="<?= $langFile->placeholder_lastname; ?>"/>
                </div>
                <div class="col-email col-md-12">
                    <input type='text' name='email-signup' placeholder="<?= $langFile->placeholder_email; ?>"/>
                </div>
                <div class="col-pwd col-md-6">
                    <input type='password' name='pwd-signup' placeholder="<?= $langFile->placeholder_password_register; ?>"/>
                </div>
                <div class="col-pwd-match col-md-6">
                    <input type='password' name='pwd-match-signup' placeholder="<?= $langFile->placeholder_password_register_match; ?>"/>
                </div>
            </div>
            <div class="checkbox-container">
                <input type="checkbox" name="cdu" value="0" id="cdu-checkbox"/>
                <label class="label-checkbox" for='cdu-checkbox'><?= $langFile->checkbox_cdu; ?></label>
            </div>
            <div class="message-contact">
                <a href="mailto:contact@worldesport.com?subject=Landing issues"><?= $langFile->email_link_landingIssues; ?></a>
            </div>
            <input type='submit' value="<?= $langFile->bt_register; ?>" name='submit-signup' class="bt-form">
            <!--<input type='submit' value='SIGN UP WITH FACEBOOK' name='subfb-signup' class="bt-form">-->
            <a style="display:none;" id="subfb-signup" href="<?= $facebookLoginUrl ?>"><span><?= $langFile->bt_registerfb; ?></span></a>
        </form>
    </div>
</div>
<!-- /Register form -->