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
                <aside class="aside aside-left">
                    <div class="timeline-container">
                        <?= $post ?>
                    </div>
                </aside>
                <aside class="aside aside-right">
                    <div id="recommended-contact-bloc">
                        <?= $recomdContacts ?>
                    </div>
                    <?php require(ROOT . 'app/Views/Footer/FooterRight.php') ?>
                </aside>
            </div>
        </div>
    </div>