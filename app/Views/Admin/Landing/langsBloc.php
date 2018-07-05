<div class="lang-bloc">
    <div class="lang-bloc-inner">
        <div class="langs-bloc">
            <h4>Website langs available</h4>
            <ul>
                <?php foreach ($we_langsArray as $lang): ?>
                    <?php if($lang->fk_langname == $currentLang): ?>
                        <li class='lang-item lang-item-active lang-selector' id="adminLang-<?= $lang->fk_langname; ?>"><?= $lang->fk_langname; ?></li>
                    <?php else: ?>
                        <li class='lang-item lang-selector' id="adminLang-<?= $lang->fk_langname; ?>"><?= $lang->fk_langname; ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <li class='new-lang-item'><input type='text' name='new-lang' placeholder="ISO_639_1"/></li>
                <li role="button" class='new-lang-item' id="add-lang-buttom">SEND</li>
                <li role="button" id="add-lang">+</li>
            </ul>
        </div>
    </div>
</div>