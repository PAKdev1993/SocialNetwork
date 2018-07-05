<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>World eSport - Reset</title>
        <link rel="icon" type="image/png" href="public/img/logo/logo-small.png"/>
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
        <link rel="stylesheet" type="text/css" href="../../public/styles/bootstrap-3.3.6-dist/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../public/styles/app.css">
        <link rel="stylesheet" type="text/css" href="../../public/styles/landing/style.css">
        <link rel="stylesheet" type="text/css" href="../../public/styles/reset/style.css">

        <link href='http://fonts.googleapis.com/css?family=Courgette' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
    </head>
    <body id="Reset">
        <div class="wrap">
            <div class="main">
                <div id="pg-1" class="pg">
                    <div class="logoWE">
                        <img src="../../public/img/logo/logo.png" alt="WorldEsport logo">
                        <h1>World eSport</h1>
                    </div>
                    <div id="signup-bloc">
                        <div id="signup-title">
                            <h1><?= $langFile->title_form ?></h1>
                        </div>
                        <div class="error">
                            <ul>
                            </ul>
                        </div>
                        <div class="form" id="signup-form">
                            <div class="messages-container col-md-12">
                                <div class="error-input-container error-field error-password-size novisible" tabindex="0">
                                    <p><?= $langFile->password_size_fail ?></p>
                                </div>
                                <div class="error-input-container error-field error-password-match novisible" tabindex="0">
                                    <p><?= $langFile->placeholder_password_match ?></p>
                                </div>
                            </div>
                            <form action='' method='post'>
                                <input type='password' name='pwd-reset' placeholder="<?= $langFile->placeholder_password ?>"/>
                                <input type='password' name='pwd-match-reset' placeholder="<?= $langFile->placeholder_password_match ?>"/>
                                <input type='submit' value='<?= $langFile->bt_reset ?>' name='reset' class="bt-form">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type='text/javascript' src="../../public/js/plugins/jquery/jquery-2.2.4.min.js"></script>
        <script type='text/javascript' src='../../public/js/functions.js'></script>
        <script type='text/javascript' src='../../public/js/landing/reset.js'></script>
    </body>
</html>
