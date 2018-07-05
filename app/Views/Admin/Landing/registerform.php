<!-- Register form -->
<div id="signup-bloc">
    <div id="signup-title" data-lang="title_form">
        <?php require 'modifsBloc.php';?>
        <h1><?= $langFile->title_form; ?></h1>
    </div>
    <div class="error">
        <ul>
        </ul>
    </div>
    <div class="success">
        <ul>
        </ul>
    </div>
    <div class="form" id="signup-form">
        <form action='' method='post'>
            <div class="row">
                <div class="col-md-4" data-lang="placeholder_firstname">
                    <span data-lang="placeholder_firstname">
                        <?php require 'modifsBloc.php';?>
                        <input type='text' name='fname-signup' placeholder="<?= $langFile->placeholder_firstname; ?>"/>
                    </span>
                </div>
                <div class="col-md-4">
                    <span data-lang="placeholder_nickname">
                        <?php require 'modifsBloc.php';?>
                        <input type='text' name='nickname-signup' placeholder="<?= $langFile->placeholder_nickname; ?>"/>
                    </span>
                </div>
                <div class="col-md-4">
                    <span data-lang="placeholder_lastname">
                        <?php require 'modifsBloc.php';?>
                        <input type='text' name='lname-signup' placeholder="<?= $langFile->placeholder_lastname; ?>"/>
                    </span>
                </div>
                <div class="col-md-12">
                    <span data-lang="placeholder_email">
                    <?php require 'modifsBloc.php';?>
                        <input type='text' name='email-signup' placeholder="<?= $langFile->placeholder_email; ?>"/>
                    </span>
                </div>
                <div class="col-md-6">
                    <span data-lang="placeholder_password_register">
                        <?php require 'modifsBloc.php';?>
                        <input type='password' name='pwd-signup' placeholder="<?= $langFile->placeholder_password_register; ?>"/>
                    </span>
                </div>
                <div class="col-md-6">
                    <span data-lang="placeholder_password_register_match">
                        <?php require 'modifsBloc.php';?>
                        <input type='password' name='pwd-match-signup' placeholder="<?= $langFile->placeholder_password_register_match; ?>"/>
                    </span>
                </div>
                <div class="col-md-12 checkbox-container">
                    <span data-lang="checkbox_cdu">
                         <?php require 'modifsBloc.php';?>
                        <input type="checkbox" name="cdu" value="0" id="cdu-checkbox">
                        <label for='cdu-checkbox'><?= $langFile->checkbox_cdu; ?></label>
                    </span>
                </div>
                <div class='col-md-12 data-lang'>
                    <span data-lang="bt_register">
                         <?php require 'modifsBloc.php';?>
                        <input type='submit' value="<?= $langFile->bt_register; ?>" name='submit-signup' class="bt-form">
                    </span>
                </div>
                <div class='col-md-12 data-lang'>
                    <span data-lang="bt_registerfb"">
                         <?php require 'modifsBloc.php';?>
                        <a id="subfb-signup" href="<?= $facebookLoginUrl ?>">
                            <span><?= $langFile->bt_registerfb; ?></span>
                        </a>
                    </span>
                </div>
            </div>
            <!--<input type='submit' value='SIGN UP WITH FACEBOOK' name='subfb-signup' class="bt-form">-->
        </form>
    </div>
</div>
<!-- /Register form -->