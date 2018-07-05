<header class="header" id="hd-home">
    <div class="header-top">
        <div id="logo-we">
            <a href="index.php"><img src="public/img/logo/logo.png" alt="WorldEsport logo"><h1>World eSport</h1></a>
        </div>
        <div class="search-bar">
            <form action='' method='post' id="log-form">
                <div class="form-elem">
                    <input type='text' name="keyword-searchbar-header" placeholder="Enter keyword" class="input input-leftpart"/>
                </div>
                <div class="form-elem rightpart-after">
                    <input type="submit" name="submit-search" class="input input-rightpart" value="">
                </div>
            </form>
        </div>
    </div>
    <nav class="menu" id="hd-menu">
        <div class="nav-menu-items">
            <ul>
                <li class="nav-item"><a id="item-home" href="index.php?p=home">HOME</a></li>
                <li class="nav-item"><a id="item-profile" href="index.php?p=profile">MY PROFILE</a></li>
                <li class="nav-item"><a id="item-community" href="index.php?p=mycommunity">MY COMMUNITY</a></li>
                <li class="nav-item"><a id="item-opportunitie">OPPORTUNITIES</a></li>
                <li class="nav-item"><a id="item-loby">LOBBY</a></li>
            </ul>
        </div>
        <div class="user-menu-items">
            <div class="nav-menu-items-container col-md-12">
                <ul>
                    <li class="user-menu-item" id="user-item-manageprofil" tabindex="1">
                        <a role="button">
                            <div class="pseudo"></div>
                        </a>
                        <div class="under-menu uder-menu-user">
                            <div class="under-menu-item-container col-md-12">
                                <div class="under-menu-item col-md-12">
                                    <div class="user-infos-container col-md-12">
                                        <div class="um-pic-container col-md-3">
                                            <div class="pic um-pic">
                                                <a id="item-profile" href="index.php?p=profile"><?= $profilePic ?> </a>
                                            </div>
                                        </div>
                                        <div class="users-ids col-md-9">
                                            <h4><?= $currentUser->nickname ?></h4>
                                            <h5><?= $currentUser->firstname ?> <?= $currentUser->lastname ?></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="under-menu-item options-item col-md-12">
                                    <a role="button"  href="#">
                                        <div class="um-item-left-part col-md-3">
                                            <span class="pseudo ps-seting"></span>
                                        </div>
                                        <div class="um-item-right-part col-md-9">
                                            <h4>Setting & Privacy</h4>
                                        </div>
                                    </a>
                                </div>
                                <div class="under-menu-item options-item col-md-12">
                                    <a role="button" href="inc/Auth/logout.php">
                                        <div class="um-item-left-part col-md-3">
                                            <span class="pseudo ps-signout"></span>
                                        </div>
                                        <div class="um-item-right-part col-md-9">
                                            <h4>Sign out</h4>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="user-menu-item" id="user-item-viewers" tabindex="2">
                        <a role="button">
                            <div class="pseudo"></div>
                        </a>
                        <div class="under-menu uder-menu-user">
                            <div class="under-menu-item-container col-md-12">
                                <div class="descript col-md-12">
                                    <p>Who has view your profil ?</p>
                                </div>
                                <div class="under-menu-item col-md-12">
                                    <div class="user-infos-container col-md-12">
                                        <div class="um-pic-container col-md-3">
                                            <div class="pic um-pic">
                                                <img src="public/img/logo/logo.png" alt="WorldEsport logo">
                                            </div>
                                        </div>
                                        <div class="users-ids col-md-9">
                                            <h4>Myd</h4>
                                            <h5>Théo Carasco</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="under-menu-item col-md-12">
                                    <div class="user-infos-container col-md-12">
                                        <div class="um-pic-container col-md-3">
                                            <div class="pic um-pic">
                                                <img src="public/img/logo/logo.png" alt="WorldEsport logo">
                                            </div>
                                        </div>
                                        <div class="users-ids col-md-9">
                                            <h4>Myd</h4>
                                            <h5>Théo Carasco</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="under-menu-item col-md-12">
                                    <div class="user-infos-container col-md-12">
                                        <div class="um-pic-container col-md-3">
                                            <div class="pic um-pic">
                                                <img src="public/img/logo/logo.png" alt="WorldEsport logo">
                                            </div>
                                        </div>
                                        <div class="users-ids col-md-9">
                                            <h4>Myd</h4>
                                            <h5>Théo Carasco</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="um-show-more col-md-12">
                                    <a role="button" href="index.php?p=profileviews">
                                        <p>Show more...</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="user-menu-item" id="user-item-notifications" tabindex="3">
                        <a role="button">
                            <div class="pseudo"></div>
                        </a>
                        <div class="under-menu uder-menu-user">
                            <div class="under-menu-item-container col-md-12">
                                <div class="under-menu-item col-md-12">
                                    <div class="user-infos-container col-md-12">
                                        <div class="um-pic-container col-md-3">
                                            <div class="pic um-pic">
                                                <img src="public/img/logo/logo.png" alt="WorldEsport logo">
                                            </div>
                                        </div>
                                        <div class="users-ids user-actions-container col-md-9">
                                            <h4>Myd</h4>
                                            <div class="user-actions col-md-12">
                                                <p class="action">liked sdgdsgds</p><p class="location"> your pos sgsdgsdgsdft</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="um-show-more col-md-12">
                                    <a role="button" href="#">
                                        <p>Show more...</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="user-menu-item" id="user-item-message" tabindex="3"><a role="button"></a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>