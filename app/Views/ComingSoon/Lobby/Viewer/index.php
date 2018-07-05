    <body id="<?= $pageName; ?>">
        <div class="wrap">
            <div class="viewer">
                <div class="viewer-content">
                </div>
            </div>
            <div class="main">
                <?= $header ?>
                <div class="body">
                    <section id="coming-soon-lobby" class="bottom">
                        <aside class="aside aside-left">

                            <div class="coming-soon-bloc-container title col-md-12">
                                <div class="coming-soon-bloc col-md-12">
                                    <h1><span class="bold"><?= $langFile->word_lobby ?></span> - <?= $langFile->word_tocome ?></h1>
                                </div>
                            </div>

                            <div class="coming-soon-bloc-container col-md-12">
                                <div class="coming-soon-bloc col-md-12">
                                    <div class="left-part col-md-4">
                                        <div class="title">
                                            <h3><?= $langFile->title_company_part ?></h3>
                                        </div>
                                        <div class="logo">
                                            <img style="opacity: 1;" src="public/img/default/company_croped.png" alt="WorldEsport Company logo">
                                        </div>
                                    </div>
                                    <div class="right-part col-md-8">
                                        <p><?= $langFile->text_company_part ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="coming-soon-bloc-container col-md-12">
                                <div class="coming-soon-bloc col-md-12">
                                    <div class="right-part col-md-8">
                                        <p><?= $langFile->text_teams_part ?></p>
                                    </div>
                                    <div class="left-part col-md-4">
                                        <div class="title">
                                            <h3><?= $langFile->title_teams_part ?></h3>
                                        </div>
                                        <div class="logo">
                                            <img style="opacity: 1;" src="public/img/default/team_croped.png" alt="WorldEsport Company logo">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="coming-soon-bloc-container col-md-12">
                                <div class="coming-soon-bloc col-md-12">
                                    <div class="left-part col-md-4">
                                        <div class="title">
                                            <h3><?= $langFile->title_groups ?></h3>
                                        </div>
                                        <div class="logo">
                                            <img style="opacity: 1;" src="public/img/default/group_croped.png" alt="WorldEsport Company logo">
                                        </div>
                                    </div>
                                    <div class="right-part col-md-8">
                                        <p><?= $langFile->text_groups ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="coming-soon-bloc-container col-md-12">
                                <div class="coming-soon-bloc col-md-12">
                                    <div class="right-part col-md-8">
                                        <p><?= $langFile->text_events_part ?></p>
                                    </div>
                                    <div class="left-part col-md-4">
                                        <div class="title">
                                            <h3><?= $langFile->title_events_part ?></h3>
                                        </div>
                                        <div class="logo">
                                            <img style="opacity: 1;" src="public/img/default/event_croped.png" alt="WorldEsport Company logo">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="coming-soon-bloc-container col-md-12">
                                <div class="coming-soon-bloc col-md-12">
                                    <div class="left-part col-md-4">
                                        <div class="title">
                                            <h3><?= $langFile->title_einfluences ?></h3>
                                        </div>
                                        <div class="logo">
                                            <img style="opacity: 1;" src="public/img/default/E-nfluencers.png" alt="WorldEsport Company logo">
                                        </div>
                                    </div>
                                    <div class="right-part col-md-8">
                                        <p><?= $langFile->text_einfluences ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="footer-mobile mobile">
                                <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
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