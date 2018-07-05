<body id="<?= $pageName; ?>">
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
    <div class="wrap">
        <div class="viewer">
            <div class="viewer-content">
            </div>
        </div>
        <div class="main">
            <?= $header ?>
            <div class="body">
                <section class="top" id="mycommunity-top">
                    <div class="header-pic-container">
                        <div class="header-pic">
                            <div id="cover-pic-container">
                                <?= $coverPic ?>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="mycommunity-bottom" class="bottom">
                    <aside class="aside aside-left">
                        <nav class="nav-community-container bloc col-md-12">
                            <ul class="list-nav-profile col-md-12">
                                <li class="item-nav-profile active">
                                    <a role="button" class="" href="#my-contacts">
                                        <p><?= $langFile->word_mes ?></p>
                                        <p><?= $langFile->nav_word_contacts ?></p>
                                    </a>
                                </li>
                                <li class="item-nav-profile" id="nav-pending-contact">
                                    <a role="button" class="" href="#my-pending-contacts">
                                        <?php if ($_COOKIE['langwe'] == 'fr'): ?>
                                            <p><?= $langFile->nav_word_contacts ?></p>
                                            <p><?= $langFile->nav_word_pending ?></p>
                                        <?php else: ?>
                                            <p><?= $langFile->nav_word_pending ?></p>
                                            <p><?= $langFile->nav_word_contacts ?></p>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li class="item-nav-profile">
                                    <a role="button" class="" href="#my-followers">
                                        <p><?= $langFile->word_mes ?></p>
                                        <p><?= $langFile->nav_word_followers ?></p>
                                    </a>
                                </li>
                                <li class="item-nav-profile">
                                    <a role="button" class="" href="#my-recommended-contacts">
                                        <?php if ($_COOKIE['langwe'] == 'fr'): ?>
                                            <p><?= $langFile->nav_word_contacts ?></p>
                                            <p><?= $langFile->nav_word_recommended ?></p>
                                        <?php else: ?>
                                            <p><?= $langFile->nav_word_recommended ?></p>
                                            <p><?= $langFile->nav_word_contacts ?></p>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <div class="community-section-container col-md-12">
                            <div class="slider-profile-chapters">
                                <section id="slide-mycontacts">
                                    <?= $contacts ?>
                                    <div class="footer-mobile mobile">
                                        <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                                    </div>
                                </section>
                                <section id="slide-mypendingcontacts">
                                    <?= $pendingContacts ?>
                                    <div class="footer-mobile mobile">
                                        <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                                    </div>
                                </section>
                                <section id="slide-myfollowers">
                                    <?= $followers ?>
                                    <div class="footer-mobile mobile">
                                        <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                                    </div>
                                </section>
                                <section id="slide-myrecommendedcontacts">
                                    <?= $recommendedContacts ?>
                                    <div class="footer-mobile mobile">
                                        <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </aside>
                    <aside class="aside aside-right">
                        <div id="recommended-contact-bloc">
                            <?= $recomdContactsRight ?>
                        </div>
                        <?php require(ROOT . 'app/Views/Footer/FooterRight.php') ?>
                    </aside>
                </section>
            </div>
        </div>
    </div>