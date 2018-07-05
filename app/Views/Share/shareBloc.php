<div class="share-bloc">
    <div class="share-bloc-left col-md-3">
        <div class="share-bloc-userpic pic">
            <a href="index.php?p=profile"><?= $profilePic ?></a>
        </div>
        <div class="share-bloc-usertitle">
            <h1 class="h1-nickname">
                <?= $currentUser->nickname ?>
            </h1>
            <h2 class="h2-name">
                <?= $currentUser->firstname ?> <?= $currentUser->lastname ?>
            </h2>
        </div>
    </div>
    <div class="share-bloc-right col-md-9">
        <div class="share-bloc-text editable-container col-md-12">
            <div class="editable-content" placeholder="<?= $langFile->placeholder_share ?>" id="share-input" contenteditable="true"></div>
            <div class="bulle-error">
                <span class="message message-top"><span><?= $langFile->title_edit ?></span></span>
                <span class="pseudo"></span>
            </div>
        </div>
        <div class="link-preview-container" data-elem="share-preview">
            <div class="loader-preview-container">
                <div class="loader-container loader-profile-elem" data-elem="loader-preview">
                    <div class="loader-double-container">
                        <span class="loader loader-double">
                        </span>
                    </div>
                </div>
            </div>
            <div class="preview-container" id="preview-content">

            </div>
        </div>
        <div class="img-share-bloc col-md-12">
            <div class="img-share-input">
                <form action='inc/Share/share.php' method='post' id="imgUploadForm" enctype="multipart/form-data">
                    <input type="file" name="pic" id="upload-pic" accept="image/*">
                    <label for="upload-pic" tabindex="0"><?= $langFile->title_select_pic ?>
                </form>
                <div class="loader-container" id="loader-imgs">
                    <span class="loader loader-double">
                    </span>
                </div>
                <div class="bulle-error size-error">
                    <span class="message message-right"><span><?= $fileErrorLangFile->error_file_too_large ?></span></span>
                    <span class="pseudo"></span>
                </div>
                <div class="bulle-error ext-error">
                    <span class="message message-right"><span><?= $fileErrorLangFile->error_file_extension ?></span></span>
                    <span class="pseudo"></span>
                </div>
                <div class="bulle-error upload-error">
                    <span class="message message-right"><span><?= $fileErrorLangFile->error_upload ?></span></span>
                    <span class="pseudo"></span>
                </div>
                <div class="bulle-error error-type">
                    <span class="message message-right"><span><?= $fileErrorLangFile->error_mime_type ?></span></span>
                    <span class="pseudo"></span>
                </div>
            </div>
        </div>
        <div class="vid-share-bloc col-md-12">
            <div class="vid-share-input">
                <form action='inc/Share/share.php' method='post' id="videoUploadForm" enctype="multipart/form-data">
                    <input type="file" name="vid" id="upload-vid" accept="video/*">
                    <label for="upload-vid" tabindex="0">Videos .mp4
                </form>
                <div class="loader-container" id="loader-videos">
                    <span class="loader loader-double">
                    </span>
                </div>
                <div class="bulle-error size-error">
                    <span class="message message-top"><span>Your image is too large, max size: 2Mo</span></span>
                    <span class="pseudo"></span>
                </div>
                <div class="bulle-error ext-error">
                    <span class="message message-top"><span>Accepted extensions: jpg, png, gif</span></span>
                    <span class="pseudo"></span>
                </div>
                <div class="bulle-error upload-error">
                    <span class="message message-top"><span>Upload has failed, try again</span></span>
                    <span class="pseudo"></span>
                </div>
                <div class="bulle-error error-type">
                    <span class="message message-top"><span>Wrong type, choose another image</span></span>
                    <span class="pseudo"></span>
                </div>
            </div>
        </div>
        <div class="share-options">
            <a role="button" class="share-option-button" id="share-images" tabindex="51"></a>
            <a role="button" class="share-option-button coming-soon" id="share-videos" tabindex="52">
                <div class="bulle-error coming-soon-msg">
                    <span class="message message-top"><span><?= $langFile->buble_comingsoon ?></span></span>
                    <span class="pseudo"></span>
                </div>
            </a>
        </div>
        <div class="share-button-container">
            <div class="share-button bt share-button-big bt-active-mask"><?= $langFile->bt_share ?></div>
            <button class="share-button bt share-button-big" id="share-post"><?= $langFile->bt_share ?></button>
        </div>
    </div>
</div>