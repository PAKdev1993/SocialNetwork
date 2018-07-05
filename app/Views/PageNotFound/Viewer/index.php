    <body id="<?= $pageName; ?>">
        <div class="wrap">
            <div class="main">
                <?= $header ?>
                <div class="body">
                    <div class="bg-container">
                        <div class="messages-container">
                            <div class="title col-md-12">
                                <h1>404</h1>
                                <p><?= $langFile->title_page_not_found ?></p>
                            </div>
                            <div class="redirect-message col-md-12">
                                <?= $message ?>
                            </div>
                        </div>
                        <img src="public/img/backgrounds/404.jpg" alt="WorldEsport 404 page">
                    </div>
                </div>
            </div>
        </div>