<?php if (!isset($navbar)) : ?>
<header>
    <div class="header-1">
        <a href="<?= $router->url('home') ?>" class="logo"> <img src="<?= BASE_URL ?>favicons/logo.svg">
        </a>
        <div class="s">
            <form method="post">
                <input type="text" placeholder="Titre, auteur" id="search">
                <label for="search" class="fas fa-search"></label>
            </form>
            <div id="resultat">
                <ul>
                    <!-- <li><?= $row->getTitle?></li> -->
                </ul>
            </div>
        </div>
    </div>
    <div class="header-2">
        <div id="menu" class="fas fa-bars"></div>
        <nav class="nav_bar">
            <ul>
                <li><a href="<?= $router->url('home') ?>"><i class="fas fa-home"></i> Accueil</a></li>
                <li><a href="#"><i class="fas fa-user-plus"></i> S'incrire</a></li>
                <li><button class="btn-login"><i class="fas fa-right-to-bracket"></i> Connexion</button></li>
                    <li><a href="#"><i class="fas fa-user"></i> Profil</a></li>
                    <li><a href="<?= $router->url('home-admin')?>"><i class="fas fa-user-tie"></i></i> Admin</a></li>
                    <li>
                        <form method="post" action="#">
                            <button type="submit">Deconnexion</button>
                        </form>
                    </li>
            </ul>
        </nav>
    </div>
</header>
<?php endif ?>