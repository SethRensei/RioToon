<?php
use Riotoon\Entity\User;
use Riotoon\Repository\UserRepository;

if (isset($_POST['login'])) {
    $repository = new UserRepository();
    if (!empty($_POST['user']) and !empty($_POST['password'])) {
        /** @var User|false */
        $user = $repository->find($_POST['user']);
        if ($user != false) {
            if (password_verify($_POST['password'], $user->getPassword())) {
                
                $_SESSION['User'] = $user->getId();
                $_SESSION['pseudo'] = $user->getPseudo();
                $_SESSION['fullname'] = unClean($user->getFullname());
                $_SESSION['roles'] = $user->getCollectionsRoles();
            } else {
                echo "<script>window.history.back();</script>";
                echo "<script>alert('Identifiant incorrect');</script>";
            }
        } else {
            echo "<script>window.history.back();</script>";
            echo "<script>alert('Identifiant incorrect');</script>";
        }
    } else {
        echo "<script>window.history.back();</script>";
        echo "<script>alert('Saisir tous les champs');</script>";
    }
}

?>

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
                </ul>
            </div>
        </div>
    </div>
    <div class="header-2">
        <div id="menu" class="fas fa-bars"></div>
        <nav class="nav_bar">
            <ul>
                <li><a href="<?= $router->url('home') ?>"><i class="fas fa-home"></i> Accueil</a></li>
                <?php if (!isset($_SESSION['User'])): ?>
                    <li><a href="<?= $router->url('signup')?>"><i class="fas fa-user-plus"></i> S'incrire</a></li>
                    <li><button class="btn-login"><i class="fas fa-right-to-bracket"></i> Connexion</button></li>
                <?php endif ?>
                <?php if (isset($_SESSION['User'])): ?>
                    <li><a href="<?= $router->url('profile', ['pseudo' => goodURL($_SESSION['pseudo'])])?>"><i class="fas fa-user"></i> Profil</a></li>
                    <?php if (in_array('ROLE_ADMIN', $_SESSION['roles'])): ?>
                    <li><a href="<?= $router->url('home-admin')?>"><i class="fas fa-user-tie"></i></i> Admin</a></li>
                    <?php endif; ?>
                    <li>
                        <form method="post" action="<?= $router->url('logout') ?>">
                            <button type="submit">Deconnexion</button>
                        </form>
                    </li>
                <?php endif ?>
            </ul>
        </nav>
    </div>
</header>

<div class="wrapper">
    <div class="content-login">
        <div class="form-box login">
            <span class="icon-close"><i class="fas fa-times"></i></span>
            <h2>Connexion</h2>
            <form method="post" id="rio-login">
                <div class="input-box">
                    <i class="fas fa-user"></i>
                    <input type="text" name="user" placeholder="Pseudo ou Email" autocomplete="off">
                    <span class="bar"></span>
                </div>
                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" autocomplete="off">
                    <span class="bar"></span>
                    <div class="view">
                        <i class="fas fa-eye"></i>
                        <i class="fas fa-eye-slash"></i>
                    </div>
                </div>
                <div class="ms-3"><a href="">Mot de passe oublié</a></div>
                <button name="login" type="submit" class="btn">Se connecter</button>
            </form>
        </div>
    </div>
</div>
<?php endif ?>