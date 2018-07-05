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
                        <div class="loader-container loader-profile-elem loader-profile-upload-logo" id="loader-cover-pic">
                            <div class="loader-double-container">
                                <span class="loader loader-double">
                                </span>
                            </div>
                        </div>
                        <div id="cover-pic-container">
                            <?= $coverPic ?>
                        </div>
                    </div>
                    <div class="edit-gear edit-header-pic ico-container" id="edit-cover-picture-container">
                        <div class="edit-cover-container">
                            <div class="ico-gear-container">
                                <div class="ico-gear">
                                </div>
                            </div>
                            <div class="text-ico">
                                <p><?= $langFileProfile->action_changecover ?></p>
                            </div>
                            <div class="cover-input-container">
                                <input name="cover-pic" id="change-cover-picture" accept="image/*" type="file">
                            </div>
                        </div>
                        <div class="bulle-error size-error">
                            <span class="message message-bottom"><span><?= $langErrorFiles->error_file_too_large ?></span></span>
                            <span class="pseudo"></span>
                        </div>
                        <div class="bulle-error ext-error">
                            <span class="message message-bottom"><span><?= $langErrorFiles->error_file_extension ?></span></span>
                            <span class="pseudo"></span>
                        </div>
                        <div class="bulle-error upload-error">
                            <span class="message message-bottom"><span><?= $langErrorFiles->error_upload ?></span></span>
                            <span class="pseudo"></span>
                        </div>
                        <div class="bulle-error error-type">
                            <span class="message message-bottom"><span><?= $langErrorFiles->error_mime_type ?></span></span>
                            <span class="pseudo"></span>
                        </div>
                    </div>
                </div>
                <div class="bd-profile-sections">
                    <div class="profile-pic-container">
                        <div class="pic">
                            <div class="loader-container loader-profile-elem loader-profile-upload-logo" id="loader-profile-pic">
                                <div class="loader-double-container">
                                    <span class="loader loader-double">
                                    </span>
                                </div>
                            </div>
                            <div id="profile-pic-container">
                                <?= $profilePic ?>
                            </div>
                        </div>
                        <div id="edit-ava-container">
                            <div class="ico-gear-container pic">
                                <div class="ico-gear">
                                    <div class="profile-input-container">
                                        <input name="profile-pic" id="change-profile-picture" accept="image/*" type="file">
                                    </div>
                                </div>
                            </div>
                            <div class="bulle-error size-error">
                                <span class="message message-bottom"><span>Your image is too large, max size: 2Mo</span></span>
                                <span class="pseudo"></span>
                            </div>
                            <div class="bulle-error ext-error">
                                <span class="message message-bottom"><span>Accepted extensions: jpg</span></span>
                                <span class="pseudo"></span>
                            </div>
                            <div class="bulle-error upload-error">
                                <span class="message message-bottom"><span>Upload has failed, try again</span></span>
                                <span class="pseudo"></span>
                            </div>
                            <div class="bulle-error error-type">
                                <span class="message message-bottom"><span>Wrong type, choose another image</span></span>
                                <span class="pseudo"></span>
                            </div>
                        </div>
                    </div>

                    <div class="bt-container" id="bt-gamer-profile">
                        <a role="button" class="bt unactive" href="index.php?p=profile&u=<?=$currentUser->slug ?>">Gamer</a>
                    </div>
                    <div class="bt-container" id="bt-employee-profile">
                        <a role="button" class="bt unactive" href="index.php?p=profile&u=<?=$currentUser->slug ?>&s=employee">Employee</a>
                    </div>
                </div>
            </section>
            <section id="myprofile-bottom" class="bottom">
                <aside class="aside aside-left">
                    <div class="profile-titles-container col-md-12">
                        <div class="col-md-12 name-container">
                            <p><?= $currentUser->firstname .' "'.$currentUser->nickname.'" '.$currentUser->lastname?></p>
                        </div>
                        <div class="col-md-6 folowers">
                            <h3 class="col-md-12"><?= $langFileProfile->title_gamer_followersection ?></h3>
                            <p class="counter-folower"><?= $nbFollowers ?></p>
                        </div>
                        <div class="col-md-6 contacts">
                            <h3 class="col-md-12"><?= $langFileProfile->title_gamer_contactsection ?></h3>
                            <p class="counter-contacts"><?= $nbContacts ?></p>
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