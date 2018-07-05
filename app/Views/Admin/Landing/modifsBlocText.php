<div class='modifs'>
    <div class="cross-close">

    </div>
    <div class="modifs-inner">
        <div class="langs-radio-bloc">
            <div class="langs-radio-bloc-inner">
                <ul>
                    <?php foreach ($we_langsArray as $lang): ?>
                        <?php if($lang->fk_langname == $currentLang): ?>
                            <li class='lang-item'>
                                <input class="radio-lang" type="radio" name="lang-to-traduce" value="<?= $lang->fk_langname; ?>" id="radio-<?= $lang->fk_langname; ?>" checked="checked">
                                <label for='radio-<?= $lang->fk_langname; ?>'><?= $lang->fk_langname; ?></label>
                            </li>
                        <?php else: ?>
                            <li class='lang-item'>
                                <input class="radio-lang" type="radio" name="lang-to-traduce" value="<?= $lang->fk_langname; ?>" id="radio-<?= $lang->fk_langname; ?>">
                                <label for='radio-<?= $lang->fk_langname; ?>'><?= $lang->fk_langname; ?></label>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="traduce-bloc traduce-bloc-text">
            <div class="loader-container">
                <span class="loader loader-double">
                </span>
            </div>
            <div class="traduce-inner">
               
            </div>
            <div class="traduce-inner-before-active">
                <p>SELECT LANG BEFORE</p>
            </div>
        </div>
    </div>
</div>
