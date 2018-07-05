<body id="<?= $pageName ?>" class="user-view" data-usr="<?= $_SESSION['auth']->pk_iduser ?>">
<div class="wrap">
    <div class="viewer">
        <div class="viewer-content">
        </div>
    </div>
    <div class="main">
        <div class="chat-maincontainer">
            <div class="chat-container">
                <div class="chat-box-grouped-maincontainer" tabindex="1">
                    <div class="chat-boxes-grouped-container"></div>
                    <div class="chat-box-grouped-bt">
                        <div class="nbnotif-container">
                            <div class="nb-notifs">
                                <p data-elem="nbNotifGrouped"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chat-boxes-container">

                </div>
            </div>
        </div>
        
        <?= $header ?>
        <div class="body">
            <section class="top" id="myprofile-top">
                <div class="header-pic-container">
                    <div class="header-pic">
                        <div id="cover-pic-container">
                            <?= $coverPic ?>
                        </div>
                    </div>
                </div>
                <div class="bd-profile-sections">
                    <div class="profile-pic-container">
                        <div class="pic">
                            <div id="profile-pic-container">
                                <?= $profilePic ?>
                            </div>
                        </div>
                        <div id="msg-user-container">
                            <div class="ico-message-container pic">
                                <div class="ico-message">
                                    <a href="#" role="button" data-elem="msg-user"></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bt-container" id="bt-gamer-profile">
                        <a role="button" class="bt" href="index.php?p=profile&u=<?=$userToDisplay->slug ?>"><?= $langFile->profilepart_title_gamer ?></a>
                    </div>
                    <div class="bt-container" id="bt-employee-profile">
                        <a role="button" class="bt unactive" href="index.php?p=profile&u=<?=$userToDisplay->slug ?>&s=employee"><?= $langFile->profilepart_title_employee ?></a>
                    </div>
                </div>
            </section>
            <section id="myprofile-bottom" class="bottom">
                <aside class="aside aside-left">
                    <div class="profile-titles-container col-md-12">
                        <div class="col-md-12 name-container">
                            <p><?= $userToDisplay->firstname .' "'.$userToDisplay->nickname.'" '.$userToDisplay->lastname?></p>
                        </div>
                        <?= $profileBlocFollowers ?>
                        <?= $profileBlocContacts ?>
                    </div>
                    <nav class="nav-profile-container bloc col-md-12">
                        <ul class="list-nav-profile col-md-12">
                            <li class="item-nav-profile active">
                                <a role="button" class="" href="#mycareer">
                                    <?php if ($_COOKIE['langwe'] == 'fr'): ?>
                                        <p><?= $langFile->bt_nav_gamer_word_career ?></p>
                                        <p><?= $langFile->bt_nav_gamer_word_esport ?></p>
                                    <?php else: ?>
                                        <p><?= $langFile->bt_nav_gamer_word_esport ?></p>
                                        <p><?= $langFile->bt_nav_gamer_word_career ?></p>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="item-nav-profile">
                                <a role="button" class="" href="#myevent">
                                    <?php if ($_COOKIE['langwe'] == 'fr'): ?>
                                        <p><?= $langFile->bt_nav_gamer_word_event ?></p>
                                        <p><?= $langFile->bt_nav_gamer_word_esport ?></p>
                                    <?php else: ?>
                                        <p><?= $langFile->bt_nav_gamer_word_esport ?></p>
                                        <p><?= $langFile->bt_nav_gamer_word_event ?></p>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="item-nav-profile">
                                <a role="button" class="" href="#mygames">
                                    <?php if ($_COOKIE['langwe'] == 'fr'): ?>
                                        <p><?= $langFile->bt_nav_gamer_word_games ?></p>
                                        <p><?= $langFile->bt_nav_gamer_word_esport ?></p>
                                    <?php else: ?>
                                        <p><?= $langFile->bt_nav_gamer_word_esport ?></p>
                                        <p><?= $langFile->bt_nav_gamer_word_games ?></p>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="item-nav-profile" id="nav-equipments">
                                <a role="button" class="" href="#myequipement">
                                    <p><?= $langFile->bt_nav_gamer_word_equipment ?></p>
                                </a>
                            </li>
                            <li class="item-nav-profile" id="nav-timeline">
                                <a role="button" class="" href="#mytimeline">
                                    <p><?= $langFile->bt_nav_gamer_word_timeline ?></p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="profile-section-container col-md-12">
                        <div id="quick-infos-bloc">
                            <?= $quickinfos ?>
                        </div>
                        <div id="album-preview-bloc-mobile" class="mobile">
                            <?= $albumPreview ?>
                        </div>
                        <div class="mobile" id="gamer-summary-bloc-mobile">
                            <?= $summary ?>
                        </div>
                        <div class="mobile" id="interests-bloc-mobile">
                            <?= $interests ?>
                        </div>
                        <div class="slider-profile-chapters">
                            <section id="slide-mycareer">
                                <?= $careerContent ?>
                                <div class="footer-mobile mobile">
                                    <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                                </div>
                            </section>
                            <section id="slide-myevents">
                                <?= $eventContent ?>
                                <div class="footer-mobile mobile">
                                    <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                                </div>
                            </section>
                            <section id="slide-mygames" >
                                <?= $gameContent ?>
                                <div class="footer-mobile mobile">
                                    <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                                </div>
                            </section>
                            <section id="slide-myequipment">
                                <?= $equipmentContent ?>
                                <div class="footer-mobile mobile">
                                    <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                                </div>
                            </section>
                            <section id="slide-mytimeline">
                                <div class="aside-left-bloc bloc timeline-bloc profile-bloc">
                                    <div class="title-aside-bloc col-md-12">
                                        <h1><?= $langFile->bt_nav_gamer_word_timeline ?></h1>
                                    </div>
                                    <div class="profile-elems-container" id="profile-timeline">
                                        <div class="timeline-container">
                                            <?= $timelineContent ?>
                                        </div>
                                    </div>
                                    <div class="bt-show-more">
                                        <div class="loader-container loader-profile-elem loader-profile-upload-logo" id="show-more-profile">
                                            <div class="loader-double-container">
                                                <span class="loader loader-double">
                                                </span>
                                            </div>
                                        </div>
                                        <a role="button" data-action="show-more-profile">
                                            <p><?= $langFile->title_myprofile_mytimeline_showmore ?></p>
                                        </a>
                                    </div>
                                </div>
                                <div class="footer-mobile mobile">
                                    <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                                </div>
                            </section>
                        </div>
                    </div>
                </aside>
                <aside class="aside aside-right">
                    <div id="album-preview-bloc">
                        <?= $albumPreview ?>
                    </div>
                    <div id="gamer-summary-bloc">
                        <?= $summary ?>
                    </div>
                    <div id="interests-bloc">
                        <?= $interests ?>
                    </div>
                    <div id="twitch-bloc">
                        <?= $blocLive ?>
                    </div>
                    <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                </aside>
            </section>
        </div>
    </div>
</div>