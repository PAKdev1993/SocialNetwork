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
                                    <h1><span class="bold"><?= $langFile->title_code_of_conduct ?></span></h1>
                                </div>
                            </div>

                            <div class="coming-soon-bloc-container col-md-12">
                                <div class="coming-soon-bloc col-md-12">
                                    <?= $langFile->cod_of_conduct ?>
                                </div>
                            </div>

                            <div class="footer-mobile mobile">
                                <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                            </div>

                        </aside>
                        <aside class="aside aside-right">
                            <?php require(ROOT . 'app/Views/Footer/FooterRight.php') ?>
                        </aside>
                    </section>
                </div>
            </div>
        </div>