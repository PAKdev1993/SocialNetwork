    <body id="<?= $pageName; ?>">
        <div class="wrap">
            <div class="viewer">
                <div class="viewer-content">
                </div>
            </div>
            <div class="main">
                <?= $header ?>
                <div class="body">
                    <section id="coming-soon-opportunities" class="bottom">
                        <aside class="aside aside-left">
                            
                            <div class="coming-soon-bloc-container title col-md-12">
                                <div class="coming-soon-bloc col-md-12">
                                    <h1><span class="bold"><?= $langFile->word_opportunities ?></span> - <?= $langFile->word_tocome ?></>
                                </div>
                            </div>

                            <div class="coming-soon-bloc-container col-md-12">
                                <div class="coming-soon-bloc col-md-12">
                                    <div class="left-part col-md-4">
                                        <div class="title">
                                            <h3><?= $langFile->title_job_part ?></h3>
                                        </div>
                                        <div class="logo">
                                            <img style="opacity: 1;" src="public/img/default/jobs_croped.png" alt="WorldEsport Company logo">
                                        </div>
                                    </div>
                                    <div class="right-part col-md-8">
                                        <p><?= $langFile->text_job_part ?></p>
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
                                            <h3><?= $langFile->title_hireme ?></h3>
                                        </div>
                                        <div class="logo">
                                            <img style="opacity: 1;" src="public/img/default/hireme_croped.png" alt="WorldEsport Company logo">
                                        </div>
                                    </div>
                                    <div class="right-part col-md-8">
                                        <p><?= $langFile->text_hireme ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="coming-soon-bloc-container col-md-12">
                                <div class="coming-soon-bloc col-md-12">
                                    <div class="right-part col-md-8">
                                        <p><?= $langFile->text_recruitme ?></p>
                                    </div>
                                    <div class="left-part col-md-4">
                                        <div class="title">
                                            <h3><?= $langFile->title_recruitme ?></h3>
                                        </div>
                                        <div class="logo">
                                            <img style="opacity: 1;" src="public/img/default/recruitme_croped.png" alt="WorldEsport Company logo">
                                        </div>
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