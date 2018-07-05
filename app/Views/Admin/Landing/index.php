<body id="<?= $pageName; ?>">
<div class="wrap">
    <?php require_once 'langsBloc.php';?>
    <div class="main">
        <!-- header -->
        <div id="hd-landing">
            <div id="logo-we">
                <a href="#"><img src="public/img/logo/logo.png" alt="WorldEsport logo"><h1>World eSport</h1></a>
            </div>
            <div class="form" id="log-form-bloc">
                <!-- log-form -->
                <form action='' method='post' id="log-form">
                    <div class="error-bloc form-elem">
                        <div class="error">
                            <ul>
                                <?php if(isset($_SESSION['flash']['facebook_error_login'])): ?>
                                    <li><?= $_SESSION['flash']['facebook_error_login']; ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="success">
                            <ul>
                            </ul>
                        </div>
                        <div class="special">
                            <ul>
                                <?php if(isset($_SESSION['flash']['special'])): ?>
                                    <li><?= $_SESSION['flash']['special']; ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <span class="log-span form-elem" id="email-log" data-lang="placeholder_email">
                        <?php require 'modifsBloc.php';?>
                        <span>
                        </span>
                        <input type='text' value="<?php if(isset($_GET['email'])): ?><?= $_GET['email']; ?><?php endif; ?>" name='email-log' placeholder="<?= $langFile->placeholder_email; ?>"/>
                    </span>
                    <span class="log-span form-elem" id="pwd-log" data-lang="placeholder_password">
                        <?php require 'modifsBloc.php';?>
                        <span>
                        </span>
                        <input type='password' name='pwd-log' placeholder="<?= $langFile->placeholder_password; ?>">
                    </span>
                    <div id="bt_send" data-lang="bt_send">
                        <?php require 'modifsBloc.php';?>
                        <input type='submit' name='signin' value='<?= $langFile->bt_send; ?>' class='form-elem'>
                    </div>
                    <div id="fb-login-container" class="form-elem" data-lang="bt_loginfb">
                        <?php require 'modifsBloc.php';?>
                        <a href = "<?= $facebookLoginUrl ?>" id="fb-login">
                            <span><?= $langFile->bt_loginfb; ?></span>
                        </a>
                    </div>
                    <div id="register" class="mobile">
                        <p><?= $langFile->bt_register; ?></p>
                    </div>
                    <div class="checkbox-container form-elem" data-lang="checkbox_rememberme">
                        <?php require 'modifsBloc.php';?>
                        <input type="checkbox" name="remember-me" value="1" id="remember-checkbox">
                        <label for='remember-checkbox'><?= $langFile->checkbox_rememberme; ?></label>
                    </div>
                    <a class="form-elem" id="pwd-forgot" href="#" role="button" data-lang="bt_passforgot">
                        <?php require 'modifsBloc.php';?>
                        <?= $langFile->bt_passforgot; ?>
                    </a>
                </form>
                <!-- /log-form -->
                <!-- send new pwd form -->
                <form action="" method="post" id="pass-forgot-form">
                    <div class="error-bloc form-elem">
                        <div class="error">
                            <ul>
                                <li>This user doesn't exist</li>
                            </ul>
                        </div>
                        <div class="success">
                            <ul>
                            </ul>
                        </div>
                        <div class="special">
                            <ul>
                            </ul>
                        </div>
                    </div>
                            <span class="pass-forgot form-elem" id="pass-forgot">
                                <span>
                                </span>
                                <input type='text' name='pass-forgot' placeholder="<?= $langFile->placeholder_email; ?>" data-lang="placeholder_email">
                            </span>
                    <input type='submit' name='send-new-pwd' value='<?= $langFile->bt_send; ?>' class='form-elem' data-lang="bt_send">
                </form>
                <!-- /send new pwd form -->
            </div>
        </div>
        <!-- /header -->
        <div class="pg" id="pg-1">
            <?php require_once 'registerform.php';?>
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
                <div id="title-network" data-lang="title_network title_network_mobile_1 title_network_mobile_2 title_network_mobile_3">
                    <?php require 'modifsBloc.php';?>
                    <h2 class="pc">
                        <?= $langFile->title_network; ?>
                    </h2>
                    <h2 class="mobile">
                        <?= $langFile->title_network_mobile_1; ?>
                    </h2>
                    <p class="mobile">
                        <?= $langFile->title_network_mobile_2; ?>
                    </p>
                    <h2 class="mobile">
                        <?= $langFile->title_network_mobile_3; ?>
                    </h2>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-user" data-lang="macaron_network_user">
                        <?php require 'modifsBloc.php';?>
                        <div class="icon-message">
                            <h3><?= $langFile->macaron_network_user; ?></h3>
                        </div>
                    </div>
                    <h3 class="mobile icon-mobile-h3" data-lang="macaron_network_user"><?= $langFile->macaron_network_user; ?></h3>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-team" data-lang="macaron_network_team">
                        <?php require 'modifsBloc.php';?>
                        <div class="icon-message">
                            <h3><?= $langFile->macaron_network_team; ?></h3>
                        </div>
                    </div>
                    <h3 class="mobile icon-mobile-h3"><?= $langFile->macaron_network_team; ?></h3>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-calendar" data-lang="macaron_network_calendar">
                        <?php require 'modifsBloc.php';?>
                        <div class="icon-message">
                            <h3><?= $langFile->macaron_network_calendar; ?></h3>
                        </div>
                    </div>
                    <h3 class="mobile icon-mobile-h3"><?= $langFile->macaron_network_calendar; ?></h3>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-loby" data-lang="macaron_network_network">
                        <?php require 'modifsBloc.php';?>
                        <div class="icon-message">
                            <h3><?= $langFile->macaron_network_network; ?></h3>
                        </div>
                    </div>
                    <h3 class="mobile icon-mobile-h3"><?= $langFile->macaron_network_network; ?></h3>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-message" data-lang="macaron_network_messages">
                        <?php require 'modifsBloc.php';?>
                        <div class="icon-message">
                            <h3><?= $langFile->macaron_network_messages; ?></h3>
                        </div>
                    </div>
                    <h3 class="mobile icon-mobile-h3"><?= $langFile->macaron_network_messages; ?></h3>
                </div>
                <div class="icon-network">
                    <div class="icon" id="icon-share" data-lang="macaron_network_share">
                        <?php require 'modifsBloc.php';?>
                        <div class="icon-message">
                            <h3 data-lang="macaron_network_share"><?= $langFile->macaron_network_share; ?></h3>
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
            <div id="our-team">
                <span style="height:40px;" data-lang="title_our_team title_about_us title_mission_statement">
                    <?php require 'modifsBloc.php';?>
                    <h2 id="our-team-title" class="team-title">
                        <?= $langFile->title_our_team; ?>
                    </h2>
                    <h2 id="aboutus-title" class="team-title">
                        <?= $langFile->title_about_us; ?>
                    </h2>
                    <h2 id="mission-title" class="team-title">
                        <?= $langFile->title_mission_statement; ?>
                    </h2>
                </span>
                <!-- Carousel -->
                <div id="our-team-carousel" class="carousel slide" data-ride="carousel">
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
                                    <span class='bg-span' id="about-us"></span>
                                </div>
                            </div>
                            <div class="p-bloc" data-lang="text_about_us-text">
                                <?php require 'modifsBlocText.php';?>
                                <p><?= $langFile->text_about_us; ?></p>
                            </div>
                        </div>
                        <div class="item mission-item">
                            <div class="picture-bloc aboutus-bloc">
                                <div class="aboutus">
                                    <span class='bg-span' id="mission-statement"></span>
                                </div>
                            </div>
                            <div class="p-bloc" data-lang="text_mission_statement-text">
                                <?php require 'modifsBlocText.php';?>
                                <p><?= $langFile->text_mission_statement; ?></p>
                            </div>
                        </div>
                        <div class="item ourteam-item">
                            <div class="picture-bloc">
                                <div class="pic">
                                    <span class='bg-span' id="marc"></span>
                                </div>
                                <div class="title" data-lang="title_marc role_marc">
                                    <?php require 'modifsBloc.php';?>
                                    <h1 class="pc">
                                        <?= $langFile->title_marc; ?>
                                    </h1>
                                    <p class="pc">
                                        <?= $langFile->role_marc; ?>
                                    </p>
                                    <h1 class="mobile">
                                        <?= $langFile->title_marc; ?> - <?= $langFile->role_marc; ?>
                                    </h1>
                                </div>
                            </div>
                            <div class="p-bloc" data-lang="text_marc-text">
                                <?php require 'modifsBlocText.php';?>
                                <p><?= $langFile->text_marc; ?></p>
                            </div>
                        </div>
                        <div class="item ourteam-item">
                            <div class="picture-bloc">
                                <div class="pic">
                                    <span class='bg-span' id="alex"></span>
                                </div>
                                <div class="title" data-lang="title_alex role_alex">
                                    <?php require 'modifsBloc.php';?>
                                    <h1 class="pc">
                                        <?= $langFile->title_alex; ?>
                                    </h1>
                                    <p class="pc">
                                        <?= $langFile->role_alex; ?>
                                    </p>
                                    <h1 class="mobile"><?= $langFile->title_alex; ?> - <?= $langFile->role_alex; ?></h1>
                                </div>
                            </div>
                            <div class="p-bloc" data-lang="text_alex-text">
                                <?php require 'modifsBlocText.php';?>
                                <p><?= $langFile->text_alex; ?></p>
                            </div>
                        </div>
                        <div class="item ourteam-item">
                            <div class="picture-bloc">
                                <div class="pic">
                                    <span class='bg-span' id="pierre"></span>
                                </div>
                                <div class="title" data-lang="title_pierre role_pierre">
                                    <?php require 'modifsBlocText.php';?>
                                    <h1 class="pc">
                                        <?= $langFile->title_pierre; ?>
                                    </h1>
                                    <p class="pc"><?= $langFile->role_pierre; ?></p>
                                    <h1 class="mobile"><?= $langFile->title_pierre; ?> - <?= $langFile->role_pierre; ?></h1>
                                </div>
                            </div>
                            <div class="p-bloc" data-lang="text_pierre-text">
                                <?php require 'modifsBlocText.php';?>
                                <p><?= $langFile->text_pierre; ?></p>
                            </div>
                        </div>
                        <div class="item ourteam-item">
                            <div class="picture-bloc">
                                <div class="pic">
                                    <span class='bg-span' id="theo"></span>
                                </div>
                                <div class="title" data-lang="title_theo role_theo">
                                    <?php require 'modifsBloc.php';?>
                                    <h1 class="pc">
                                        <?= $langFile->title_theo; ?>
                                    </h1>
                                    <p class="pc">
                                        <?= $langFile->role_theo; ?>
                                    </p>
                                    <h1 class="mobile"><?= $langFile->title_theo; ?> - <?= $langFile->role_theo; ?></h1>
                                </div>
                            </div>
                            <div class="p-bloc" data-lang="text_theo-text">
                                <?php require 'modifsBlocText.php';?>
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
                <div id="title-loby" data-lang="title_our_loby">
                    <?php require 'modifsBloc.php';?>
                    <h2>
                        <?= $langFile->title_our_loby; ?>
                    </h2>
                </div>
                <!-- /loby carousel PC -->
                <div id="icons-loby-carousel" class="pc" data-lang="macaron_loby_company title_company_mobile macaron_loby_team title_team_mobile macaron_loby_groups title_groups_mobile macaron_loby_events title_events_mobile">
                    <?php require 'modifsBloc.php';?>
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
                                        <div class="icon-message" >
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
                            <h3><?= $langFile->title_company_mobile; ?></h3>
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
                            <h3><?= $langFile->title_team_mobile; ?></h3>
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
                            <h3><?= $langFile->title_groups_mobile; ?></h3>
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
                            <h3><?= $langFile->title_events_mobile; ?></h3>
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
                    <h1>Terms & Conditions</h1>
                    <div class="doc-content">

                    </div>
                </div>
            </div>
            <div class="doc" id="Privacy">
                <div class="doc-container">
                    <h1>Privacy</h1>
                    <div class="doc-content">
                        <p>

                            Illud tamen te esse admonitum volo, primum ut qualis es talem te esse omnes existiment ut, quantum a rerum turpitudine abes, tantum te a verborum libertate seiungas; deinde ut ea in alterum ne dicas, quae cum tibi falso responsa sint, erubescas. Quis est enim, cui via ista non pateat, qui isti aetati atque etiam isti dignitati non possit quam velit petulanter, etiamsi sine ulla suspicione, at non sine argumento male dicere? Sed istarum partium culpa est eorum, qui te agere voluerunt; laus pudoris tui, quod ea te invitum dicere videbamus, ingenii, quod ornate politeque dixisti.

                            Illud tamen clausos vehementer angebat quod captis navigiis, quae frumenta vehebant per flumen, Isauri quidem alimentorum copiis adfluebant, ipsi vero solitarum rerum cibos iam consumendo inediae propinquantis aerumnas exitialis horrebant.

                            Nec piget dicere avide magis hanc insulam populum Romanum invasisse quam iuste. Ptolomaeo enim rege foederato nobis et socio ob aerarii nostri angustias iusso sine ulla culpa proscribi ideoque hausto veneno voluntaria morte deleto et tributaria facta est et velut hostiles eius exuviae classi inpositae in urbem advectae sunt per Catonem, nunc repetetur ordo gestorum.

                            Unde Rufinus ea tempestate praefectus praetorio ad discrimen trusus est ultimum. ire enim ipse compellebatur ad militem, quem exagitabat inopia simul et feritas, et alioqui coalito more in ordinarias dignitates asperum semper et saevum, ut satisfaceret atque monstraret, quam ob causam annonae convectio sit impedita.

                            Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.

                            Hac ita persuasione reducti intra moenia bellatores obseratis undique portarum aditibus, propugnaculis insistebant et pinnis, congesta undique saxa telaque habentes in promptu, ut si quis se proripuisset interius, multitudine missilium sterneretur et lapidum.

                            Alii nullo quaerente vultus severitate adsimulata patrimonia sua in inmensum extollunt, cultorum ut puta feracium multiplicantes annuos fructus, quae a primo ad ultimum solem se abunde iactitant possidere, ignorantes profecto maiores suos, per quos ita magnitudo Romana porrigitur, non divitiis eluxisse sed per bella saevissima, nec opibus nec victu nec indumentorum vilitate gregariis militibus discrepantes opposita cuncta superasse virtute.

                            Quibus ita sceleste patratis Paulus cruore perfusus reversusque ad principis castra multos coopertos paene catenis adduxit in squalorem deiectos atque maestitiam, quorum adventu intendebantur eculei uncosque parabat carnifex et tormenta. et ex is proscripti sunt plures actique in exilium alii, non nullos gladii consumpsere poenales. nec enim quisquam facile meminit sub Constantio, ubi susurro tenus haec movebantur, quemquam absolutum.

                            Has autem provincias, quas Orontes ambiens amnis imosque pedes Cassii montis illius celsi praetermeans funditur in Parthenium mare, Gnaeus Pompeius superato Tigrane regnis Armeniorum abstractas dicioni Romanae coniunxit.

                            Illud tamen te esse admonitum volo, primum ut qualis es talem te esse omnes existiment ut, quantum a rerum turpitudine abes, tantum te a verborum libertate seiungas; deinde ut ea in alterum ne dicas, quae cum tibi falso responsa sint, erubescas. Quis est enim, cui via ista non pateat, qui isti aetati atque etiam isti dignitati non possit quam velit petulanter, etiamsi sine ulla suspicione, at non sine argumento male dicere? Sed istarum partium culpa est eorum, qui te agere voluerunt; laus pudoris tui, quod ea te invitum dicere videbamus, ingenii, quod ornate politeque dixisti.

                            Et eodem impetu Domitianum praecipitem per scalas itidem funibus constrinxerunt, eosque coniunctos per ampla spatia civitatis acri raptavere discursu. iamque artuum et membrorum divulsa conpage superscandentes corpora mortuorum ad ultimam truncata deformitatem velut exsaturati mox abiecerunt in flumen.

                            In his tractibus navigerum nusquam visitur flumen sed in locis plurimis aquae suapte natura calentes emergunt ad usus aptae multiplicium medelarum. verum has quoque regiones pari sorte Pompeius Iudaeis domitis et Hierosolymis captis in provinciae speciem delata iuris dictione formavit.

                            Hac ex causa conlaticia stipe Valerius humatur ille Publicola et subsidiis amicorum mariti inops cum liberis uxor alitur Reguli et dotatur ex aerario filia Scipionis, cum nobilitas florem adultae virginis diuturnum absentia pauperis erubesceret patris.

                            Novitates autem si spem adferunt, ut tamquam in herbis non fallacibus fructus appareat, non sunt illae quidem repudiandae, vetustas tamen suo loco conservanda; maxima est enim vis vetustatis et consuetudinis. Quin in ipso equo, cuius modo feci mentionem, si nulla res impediat, nemo est, quin eo, quo consuevit, libentius utatur quam intractato et novo. Nec vero in hoc quod est animal, sed in iis etiam quae sunt inanima, consuetudo valet, cum locis ipsis delectemur, montuosis etiam et silvestribus, in quibus diutius commorati sumus.

                            Quibus ita sceleste patratis Paulus cruore perfusus reversusque ad principis castra multos coopertos paene catenis adduxit in squalorem deiectos atque maestitiam, quorum adventu intendebantur eculei uncosque parabat carnifex et tormenta. et ex is proscripti sunt plures actique in exilium alii, non nullos gladii consumpsere poenales. nec enim quisquam facile meminit sub Constantio, ubi susurro tenus haec movebantur, quemquam absolutum.
                        </p>
                    </div>
                </div>
            </div>
            <div class="doc" id="CodeConduct">
                <div class="doc-container">
                    <h1>Code of Conduct</h1>
                    <div class="doc-content">
                    </div>
                </div>
            </div>
            <footer data-lang="bt_terms_and_conditions bt_privacy bt_code_of_conducts bt_languages">
                <?php require 'modifsBloc.php';?>
                <p class="col-md-4">Copyright © 2016 World eSport Ltd.</p>
                <div class="col-md-4" id="legals-mentions">
                    <span role="button" class="terms" data-lang="bt_terms_and_conditions"><?= $langFile->bt_terms_and_conditions; ?> |</span>
                    <span href="#" role="button" class="privacy"  data-lang="bt_privacy"><?= $langFile->bt_privacy; ?>  |</span>
                    <span href="#" role="button" class="codeofconduct" data-lang="bt_code_of_conducts"><?= $langFile->bt_code_of_conducts; ?></span>
                </div>
                <div class="col-md-4 dropup" id="languages" data-lang="bt_languages">
                    <button aria-expanded="true" class="dropdown-toggle" type="button" id="language-menu" data-toggle="dropdown">
                        <?= $langFile->bt_languages; ?>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="language-menu">
                        <?php foreach ($we_langsArray as $lang): ?>
                            <li role="presentation"><a role="menuitem" href="#" class="lang-selector" id="lang-<?= $lang->fk_langname; ?>"><?= $lang->fk_langname; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </footer>
        </div>
    </div>
</div>