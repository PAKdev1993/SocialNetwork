<body id="<?= $pageName; ?>" data-usr="<?= $_SESSION['auth']->pk_iduser ?>">
<div class="wrap">
    <div class="viewer">
        <div class="viewer-content">
        </div>
    </div>
    <div class="main">
        <?= $header ?>
        <div class="body">
            <section class="top" id="searchpage-top">
                <div class="header-pic-container">
                    <div class="header-pic">
                        <div id="cover-pic-container">
                            <?= $coverPic ?>
                        </div>
                    </div>
                </div>
            </section>
            <section id="searchpage-bottom" class="bottom">
                <aside class="aside aside-left">
                    <div class="community-section-container col-md-12">
                        <div class="slider-searchpage-chapter">
                            <section id="slide-searchresults">
                                <?= $searchBar ?>
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