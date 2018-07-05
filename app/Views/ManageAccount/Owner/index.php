<body id="<?= $pageName; ?>" data-usr="<?= $_SESSION['auth']->pk_iduser ?>">
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
                <section class="top" id="manageaccount-top">
                    <div class="header-pic-container">
                        <div class="header-pic">
                            <div id="cover-pic-container">
                                <?= $coverPic ?>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="manageaccount-bottom" class="bottom">
                    <aside class="aside aside-left">
                        <div class="manageaccount-section-container col-md-12">
                            <div class="slider-manageaccount-chapters">
                                <section id="slide-manageaccount">
                                    <?= $manageAccountPanel ?>
                                </section>
                                <div class="footer-mobile mobile">
                                    <?php require(ROOT .'app/Views/Footer/FooterRight.php') ?>
                                </div>
                            </div>
                        </div>
                    </aside>
                    <aside class="aside aside-right">
                        <?php require(ROOT . 'app/Views/Footer/FooterRight.php') ?>
                    </aside>
                </section>
            </div>
        </div>
    </div>