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
    <div class="main a_active-galery">
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
                    </div>

                    <div class="bt-container" id="bt-employee-profile">
                        <a role="button" class="bt unactive" href="index.php?p=profile&u=<?=$userToDisplay->slug ?>">Gamer</a>
                    </div>
                    <div class="bt-container" id="bt-gamer-profile">
                        <a role="button" class="bt unactive" href="index.php?p=profile&u=<?=$userToDisplay->slug ?>&s=employee">Employee</a>
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
                        <div class="col-md-6 contacts">
                            <h3 class="col-md-12">Contacts</h3>
                            <p class="counter-contacts"><?= $nbContacts ?></p>
                            <div class="bt-add-container">
                                <button class="share-button bt" id="add-contact">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                </aside>
            </section>
            <section id="galery">
                <?= $albumContent; ?>
            </section>
        </div>
    </div>
</div>