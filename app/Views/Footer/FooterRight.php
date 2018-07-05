<div class="bloc" id="footer">
    <div id="lig-1">
        <div class="dropdown" id="languages">
            <button aria-expanded="true" class="dropdown-toggle" type="button" id="language-menu" data-toggle="dropdown">
                <?= $langFooter->footer_bt_languages; ?>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="language-menu">
                <?php foreach ($we_langsArray as $lang): ?>
                    <li role="presentation"><a role="menuitem" href="#" class="lang-selector" id="lang-<?= $lang->langname; ?>"><?= $lang->langname; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div id='lig-2'>
        <a role="button" href="index.php?p=about-us"> <?= $langFooter->footer_aboutus; ?> |</a>
        <a role="button" data-action="Contactus"> <?= $langFooter->footer_contactus; ?> |</a>
        <a role="button" data-action="ReportAbuse"> <?= $langFooter->footer_reportabuse; ?> |</a>
        <a role="button" href="index.php?p=terms-and-conditions"> <?= $langFooter->footer_termsandconditions; ?> |</a>
        <a role="button" href="index.php?p=privacy"> <?= $langFooter->footer_privacy; ?> |</a>
        <a role="button" href="index.php?p=code-of-conduct"> <?= $langFooter->footer_codeofconduct; ?> </a>
    </div>
    <div id='lig-3'>
        <div class="icon-normal fb-icon">
            <a href="http://www.facebook.com/worldesport.ltd" target="_blank"></a>
        </div>
        <div class="icon-normal twitter-icon">
            <a href="http://www.twitter.com/WorldeSport_Ltd" target="_blank"></a>
        </div>
        <div class="icon-normal yt-icon">
            <a href="https://www.youtube.com/channel/UCeN50kq2h5VjoK5uwFU_n3Q"></a>
        </div>
        <div class="icon-normal insta-icon">
            <a href="http://www.instagram.com/worldesport.ltd" target="_blank"></a>
        </div>
    </div>
    <div id='lig-4'>
        <p>Copyright © 2016</p><span class="logo-we"></span><p>World eSport Ltd</p>
    </div>
</div>