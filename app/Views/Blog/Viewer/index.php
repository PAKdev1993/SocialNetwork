<body id="<?= $pageName; ?>">
<div class="wrap">
    <div class="viewer">
        <div class="viewer-content">
        </div>
    </div>
    <div class="main">
        <?= $header ?>
        <div class="body">
            <section class="top" id="message-center-top">
                <div id="testLoad">

                </div>
            </section>
            <section class="aside-right" id="message-center-bottom">
                <?php require(ROOT . '/app/Views/Footer/FooterRight.php') ?>
            </section>
        </div>
    </div>
</div>
<?php
// Get the JSON
$json = file_get_contents('http://blog.worldesport.com/wp-json/posts?filter[posts_per_page]=4');
// Convert the JSON to an array of posts
$posts = json_decode($json);
var_dump($posts)
?>