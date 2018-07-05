<body id="<?= $pageName; ?>" data-usr="<?= $_SESSION['auth']->pk_iduser ?>">
<div class="wrap">
    <div class="viewer">
        <div class="viewer-content">
        </div>
    </div>
    <div class="main">
        <?= $header ?>
        <div class="body">
            <section class="top" id="message-center-top">
                <div class="message-center-content">
                    <div class="contact-part col-md-3">
                        <div class="header-part discussion-header col-md-12">
                            <h4>Contacts</h4>
                        </div>
                        <div class="body-part col-md-12">
                            <div class="search-bar-container col-md-12">
                                <div class="search-bar col-md-12">
                                    <div class="input-container input col-md-12">
                                        <input class="" type="text" name="search-conversation" value="" placeholder="Rechercher ...">
                                    </div>
                                </div>
                            </div>
                            <div class="search-result-container" data-elem="search-results">
                            </div>
                            <?= $convsApercus ?>
                        </div>
                    </div>
                    <div class="discussion-part">
                        <div class="loader-container loader-profile-elem loader-profile-upload-logo" data-elem="loader-discussion-part">
                            <div class="loader-double-container">
                            <span class="loader loader-double">
                            </span>
                            </div>
                        </div>
                        <div class="discussion-container" data-elem="discussion-container">

                        </div>
                    </div>
                </div>
            </section>
            <section class="aside-right" id="message-center-bottom">
                <?php require(ROOT . '/app/Views/Footer/FooterRight.php') ?>
            </section>
        </div>
    </div>
</div>