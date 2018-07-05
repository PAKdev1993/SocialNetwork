<div class="aside-right-bloc bloc invite-your-friends">
    <div class="title-aside-bloc col-md-12">
        <h1><?= $langFile->title_bloc_invitation ?></h1>
    </div>
    <div class="bloc-container" id="invitation-container">
        <div class="descript">
            <p><?= $langFile->text_bloc_invitation ?></p>
        </div>
        <div class="messages-container col-md-12">
            <div class="error-input-container error-field error-email novisible" tabindex="0">
                <p><?= $langFile->error_invalid_email ?></p>
            </div>
        </div>
        <div class="mails-container col-md-12">
            <div class="mail-bloc">
                <input class="input" type="text" name="mails-toinvite" placeholder="<?= $langFile->placeholder_input_invitation ?>">
            </div>
        </div>
        <div class="send-inv-container col-md-12">
            <button data-action="invite" class="share-button bt">
                <?= $langFile->bt_send_invitation ?>
            </button>
        </div>
    </div>
</div>