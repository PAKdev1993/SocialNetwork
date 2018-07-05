<body>
<div class="wrap">
    <div class="main">
        <div class="pg" id="pg-1">
            <!-- header -->
            <div id="hd-landing">
                <div id="logo-we">
                    <a href="#"><img src="public/img/logo/logo.png" alt="WorldEsport logo"><span class="title-container"><h1>World eSport</h1><h5>Take <span class="orange">eSport</span> to the next level</h5></span></a>
                </div>
                <div class="form" id="log-form-bloc">
                    <!-- log-form -->
                    <form action='' method='post' id="log-form">
                        <div class="checkbox-container form-elem">
                            <input type="checkbox" name="remember-me" value="1" id="remember-checkbox">
                            <label for='remember-checkbox'><?= $langFile->checkbox_rememberme; ?></label>
                        </div>
                        <div id="fb-login-container" class="form-elem" style="display:none;">
                            <a href = "<?= $facebookLoginUrl ?>" id="fb-login">
                                <span><?= $langFile->bt_loginfb; ?></span>
                            </a>
                        </div>
                        <input type='submit' name='signin' value='<?= $langFile->bt_send; ?>' class='form-elem pc'>
                            <span class="log-span form-elem" id="pwd-log">
                                <span>
                                </span>
                                <input type='password'  tabindex="2" name='pwd-log' placeholder="<?= $langFile->placeholder_password; ?>">
                            </span>
                            <span class="log-span form-elem" id="email-log">
                                <span>
                                </span>
                                <input type='text'  tabindex="1" value="<?php if(isset($_GET['email'])): ?><?= $_GET['email']; ?><?php endif; ?>" name='email-log' placeholder="<?= $langFile->placeholder_email; ?>"/></span>
                        <div class="messages-container-log form-elem">
                            <div class="error-input-container error-field error-emaillog-invalid novisible" tabindex="0">
                                <p><?= $langFile->login_fail; ?></p>
                            </div>
                            <div class="special-input-container error-field error-confirm-account novisible" tabindex="0">
                                <p><?= $langFile->confirm_fail; ?></p>
                            </div>
                        </div>
                        <input type='submit' name='signin' value='<?= $langFile->bt_send; ?>' class='form-elem mobile'>
                        <div id="register" class="mobile">
                            <p><?= $langFile->bt_register; ?></p>
                        </div>
                        <a class="form-elem" id="pwd-forgot" href="#" role="button">
                            <?= $langFile->bt_passforgot; ?>
                        </a>
                    </form>
                    <!-- /log-form -->
                    <!-- send new pwd form -->
                    <form action="" method="post" id="pass-forgot-form">
                        <div class="error-bloc messages-container-log form-elem">
                            <div class="special-input-container error-field error-forgot-confirm novisible" tabindex="0">
                                <p><?= $langFile->passforgot_confirm_fail; ?></p>
                            </div>
                            <div class="error-input-container error-field error-forgot-exist novisible" tabindex="0">
                                <p><?= $langFile->passforgot_user_fail; ?></p>
                            </div>
                            <div class="error-input-container error-field error-forgot-email novisible" tabindex="0">
                                <p><?= $langFile->email_fail; ?></p>
                            </div>
                            <div class="valid-input-container error-field valid-forgot novisible" tabindex="0">
                                <p><?= $langFile->passforgot_confirm_send; ?></p>
                            </div>
                        </div>
                            <span class="pass-forgot form-elem" id="pass-forgot">
                                <span>
                                </span>
                                <input type='text' name='pass-forgot' placeholder="<?= $langFile->placeholder_email; ?>">
                            </span>
                        <div class="bt-container form-elem">
                            <div class="loader-container loader-profile-elem loader-profile-upload-logo" id="loader-pass-forgot">
                                <div class="loader-double-container">
                                        <span class="loader loader-double">
                                        </span>
                                </div>
                            </div>
                            <input type='submit' name='send-new-pwd' value='<?= $langFile->bt_send; ?>'>
                        </div>

                    </form>
                    <!-- /send new pwd form -->
                </div>
            </div>
            <!-- /header -->
            <?php require_once('registerform.php');?>
            <div class="land-pg-suiv next tovid">

            </div>
        </div>
        <div class="pg" id="pg-2">
            <!-- Vidéo bloc -->
            <video preload="auto" controls poster="public/img/video/poster.png" id="vid">
                <source src="public/img/video/World eSport Ltd - Introduction trailer EN.mp4" type="video/mp4"/>
            </video>
            <!-- /Vidéo bloc -->
            <div class="land-pg-suiv next tovid">

            </div>
        </div>
        <div class="pg" id="pg-3">
            <!-- Icons -->
            <div id="icon-network-bloc">
                <div id="title-network">
                    <h2 class="pc"><?= $langFile->title_network; ?></h2>
                    <h2 class="mobile"><?= $langFile->title_network_mobile_1; ?></h2>
                    <p class="mobile"><?= $langFile->title_network_mobile_2; ?></p>
                    <h2 class="mobile"><?= $langFile->title_network_mobile_3; ?></h2>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-user">
                        <div class="icon-message">
                            <h3><?= $langFile->macaron_network_user; ?></h3>
                        </div>
                    </div>
                    <h3 class="mobile icon-mobile-h3"><?= $langFile->macaron_network_user; ?></h3>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-team">
                        <div class="icon-message">
                            <h3><?= $langFile->macaron_network_team; ?></h3>
                        </div>
                    </div>
                    <h3 class="mobile icon-mobile-h3"><?= $langFile->macaron_network_team; ?></h3>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-calendar">
                        <div class="icon-message">
                            <h3><?= $langFile->macaron_network_calendar; ?></h3>
                        </div>
                    </div>
                    <h3 class="mobile icon-mobile-h3"><?= $langFile->macaron_network_calendar; ?></h3>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-loby">
                        <div class="icon-message">
                            <h3><?= $langFile->macaron_network_network; ?></h3>
                        </div>
                    </div>
                    <h3 class="mobile icon-mobile-h3"><?= $langFile->macaron_network_network; ?></h3>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-message">
                        <div class="icon-message">
                            <h3><?= $langFile->macaron_network_messages; ?></h3>
                        </div>
                    </div>
                    <h3 class="mobile icon-mobile-h3"><?= $langFile->macaron_network_messages; ?></h3>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-share">
                        <div class="icon-message">
                            <h3><?= $langFile->macaron_network_share; ?></h3>
                        </div>
                    </div>
                    <h3 class="mobile icon-mobile-h3"><?= $langFile->macaron_network_share; ?></h3>
                </div>
            </div>
            <!-- /Icons -->
            <div class="land-pg-suiv next" id="to-pg-4">

            </div>
        </div>
        <div class="pg" id="pg-4">
            <div class="body-pg-4">
                <div id="our-team">
                    <h2 id="our-team-title" class="team-title"><?= $langFile->title_our_team; ?></h2>
                    <h2 id="aboutus-title" class="team-title"><?= $langFile->title_about_us; ?></h2>
                    <h2 id="mission-title" class="team-title"><?= $langFile->title_mission_statement; ?></h2>
                    <!-- Carousel -->
                    <div id="our-team-carousel" class="carousel slide" data-ride="carousel" data-interval="false">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#our-team-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#our-team-carousel" data-slide-to="1"></li>
                            <li data-target="#our-team-carousel" data-slide-to="2"></li>
                            <li data-target="#our-team-carousel" data-slide-to="3"></li>
                            <li data-target="#our-team-carousel" data-slide-to="4"></li>
                            <li data-target="#our-team-carousel" data-slide-to="5"></li>
                        </ol>
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <div class="item aboutus-item active">
                                <div class="picture-bloc aboutus-bloc">
                                    <div class="aboutus">
                                        <img src="public/img/ourteam/aboutus.png">
                                    </div>
                                </div>
                                <div class="p-bloc">
                                    <?= $langFile->text_about_us; ?>
                                </div>
                            </div>
                            <div class="item mission-item">
                                <div class="picture-bloc aboutus-bloc">
                                    <div class="aboutus">
                                        <img src="public/img/ourteam/missionstatement.png">
                                    </div>
                                </div>
                                <div class="p-bloc">
                                    <?= $langFile->text_mission_statement; ?>
                                </div>
                            </div>
                            <div class="item ourteam-item">
                                <div class="picture-bloc">
                                    <div class="pic">
                                        <span class='bg-span' id="marc"></span>
                                    </div>
                                    <div class="title">
                                        <h1 class="pc">
                                            <?= $langFile->title_marc; ?>
                                        </h1>
                                        <p class="pc"><?= $langFile->role_marc; ?></p>
                                        <h1 class="mobile"><?= $langFile->title_marc; ?> - <?= $langFile->role_marc; ?></h1>
                                    </div>
                                </div>
                                <div class="p-bloc">
                                    <?= $langFile->text_marc; ?>
                                </div>
                            </div>
                            <div class="item ourteam-item">
                                <div class="picture-bloc">
                                    <div class="pic">
                                        <span class='bg-span' id="alex"></span>
                                    </div>
                                    <div class="title">
                                        <h1 class="pc">
                                            <?= $langFile->title_alex; ?>
                                        </h1>
                                        <p class="pc"><?= $langFile->role_alex; ?></p>
                                        <h1 class="mobile"><?= $langFile->title_alex; ?> - <?= $langFile->role_alex; ?></h1>
                                    </div>
                                </div>
                                <div class="p-bloc">
                                    <?= $langFile->text_alex; ?>
                                </div>
                            </div>
                            <div class="item ourteam-item">
                                <div class="picture-bloc">
                                    <div class="pic">
                                        <span class='bg-span' id="pierre"></span>
                                    </div>
                                    <div class="title">
                                        <h1 class="pc">
                                            <?= $langFile->title_pierre; ?>
                                        </h1>
                                        <p class="pc"><?= $langFile->role_pierre; ?></p>
                                        <h1 class="mobile"><?= $langFile->title_pierre; ?> - <?= $langFile->role_pierre; ?></h1>
                                    </div>
                                </div>
                                <div class="p-bloc">
                                    <?= $langFile->text_pierre; ?>
                                </div>
                            </div>
                            <div class="item ourteam-item">
                                <div class="picture-bloc">
                                    <div class="pic">
                                        <span class='bg-span' id="theo"></span>
                                    </div>
                                    <div class="title">
                                        <h1 class="pc">
                                            <?= $langFile->title_theo; ?>
                                        </h1>
                                        <p class="pc"><?= $langFile->role_theo; ?></p>
                                        <h1 class="mobile"><?= $langFile->title_theo; ?> - <?= $langFile->role_theo; ?></h1>
                                    </div>
                                </div>
                                <div class="p-bloc">
                                    <?= $langFile->text_theo; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Carousel -->
                    <div class="land-pg-suiv pc">

                    </div>
                    <div class="land-pg-suiv mobile" id="our-loby-pg-suiv">

                    </div>
                </div>
                <div id="explore-loby">
                    <div id="title-loby">
                        <h2><?= $langFile->title_our_loby; ?></h2>
                    </div>
                    <!-- /loby carousel PC -->
                    <div id="icons-loby-carousel" class="pc">
                        <div id="icon-loby-bloc">
                            <ul>
                                <li>
                                    <div class="icon-network">
                                        <div class="icon" id="icon-company">
                                            <div class="icon-message">
                                                <h3><?= $langFile->macaron_loby_company; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon-network">
                                        <div class="icon" id="icon-teams">
                                            <div class="icon-message">
                                                <h3><?= $langFile->macaron_loby_team; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon-network">
                                        <div class="icon" id="icon-groups">
                                            <div class="icon-message">
                                                <h3><?= $langFile->macaron_loby_groups; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon-network">
                                        <div class="icon" id="icon-events">
                                            <div class="icon-message">
                                                <h3><?= $langFile->macaron_loby_events; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /loby carousel PC -->
                    <div id="bt-bloc" class="pc">
                        <div class="bt" id="bt-left" role="button">
                        </div>
                        <div class="bt" id="bt-right" role="button">
                        </div>
                    </div>
                    <!-- loby carousel Mobile -->
                    <div id="our-lobby-carousel" class="carousel slide mobile" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#our-lobby-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#our-lobby-carousel" data-slide-to="1"></li>
                            <li data-target="#our-lobby-carousel" data-slide-to="2"></li>
                            <li data-target="#our-lobby-carousel" data-slide-to="3"></li>
                        </ol>
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                                <h3>Companies</h3>
                                <div class="icon-network">
                                    <div class="icon" id="icon-company-mobile">
                                        <div class="icon-message">
                                            <h3><?= $langFile->macaron_loby_company; ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <h3><?= $langFile->macaron_loby_company; ?></h3>
                            </div>
                            <div class="item">
                                <h3>Teams</h3>
                                <div class="icon-network">
                                    <div class="icon" id="icon-teams-mobile">
                                        <div class="icon-message">
                                            <h3><?= $langFile->macaron_loby_team; ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <h3><?= $langFile->macaron_loby_team; ?></h3>
                            </div>
                            <div class="item">
                                <h3>Groups</h3>
                                <div class="icon-network">
                                    <div class="icon" id="icon-groups-mobile">
                                        <div class="icon-message">
                                            <h3><?= $langFile->macaron_loby_groups; ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <h3><?= $langFile->macaron_loby_groups; ?></h3>
                            </div>
                            <div class="item">
                                <h3>Events</h3>
                                <div class="icon-network">
                                    <div class="icon" id="icon-events-mobile">
                                        <div class="icon-message">
                                            <h3><?= $langFile->macaron_loby_events; ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <h3><?= $langFile->macaron_loby_events; ?></h3>
                            </div>
                        </div>
                    </div>
                    <!-- /loby carousel Mobile -->
                </div>
                <div class="doc" id="terms">
                    <div class="doc-container">
                        <h1><?= $langFile->bt_terms_and_conditions; ?></h1>
                        <div class="doc-content">
                            <?= $langwTermsandconditions ?>
                        </div>
                    </div>
                </div>
                <div class="doc" id="Privacy">
                    <div class="doc-container">
                        <h1><?= $langFile->bt_privacy; ?></h1>
                        <div class="doc-content">
                            <?= $langPrivacy ?>
                        </div>
                    </div>
                </div>
                <div class="doc" id="CodeConduct">
                    <div class="doc-container">
                        <h1><?= $langFile->bt_code_of_conducts; ?></h1>
                        <div class="doc-content">
                            <?= $langCodOfConduct ?>
                        </div>
                    </div>
                </div>
            </div>
            <footer>
                <p class="col-md-3">Copyright © 2016 World eSport Ltd.</p>
                <div class="col-md-6" id="legals-mentions">
                    <span role="button" class="terms"><?= $langFile->bt_terms_and_conditions; ?> |</span>
                    <span href="#" role="button" class="privacy"><?= $langFile->bt_privacy; ?>  |</span>
                    <span href="#" role="button" class="codeofconduct"><?= $langFile->bt_code_of_conducts; ?></span>
                </div>
                <div class="footer-lang-container col-md-3">
                    <div class="dropup" id="languages">
                        <button aria-expanded="true" class="dropdown-toggle" type="button" id="language-menu" data-toggle="dropdown">
                            <?= $langFile->bt_languages; ?>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="language-menu">
                            <?php foreach ($we_langsArray as $lang): ?>
                                <li role="presentation"><a role="menuitem" href="#" class="lang-selector" id="lang-<?= $lang->langname; ?>"><?= $lang->langname; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

