<?php

use Riotoon\Entity\User;
use Riotoon\Service\BuildErrors;
use Riotoon\Repository\UserRepository;
use Riotoon\Service\LtAvatar;

$pseudo = $params['pseudo'];
$repository = new UserRepository();

/** @var User|false */
$user = $repository->find($pseudo);
if ($user === false)
    throw new Exception("Aucun utilisateur trouvé sous '$pseudo'");

$errors = [];
$url = $router->url('profile', ['pseudo' => goodURL($user->getPseudo())]);
$count_like = $repository->getCountLike($user->getId())['user_count'];
$count_dislike = $repository->getCountDislike($user->getId())['user_count'];

// Change the password
if (isset($_POST['change-password'], $_POST['old-pass'], $_POST['new-pass'])) {
    if (password_verify($_POST['old-pass'], $user->getPassword())) {
        if ($_POST['old-pass'] != $_POST['new-pass']) {
            $user->setPassword($_POST['new-pass']);
            $repository->editPassword($user);
            $_POST = [];
            $_SESSION['success'] = true;
            $_SESSION['content'] = 'Vous avez modifié (e) votre mot de passe';
        } else
            BuildErrors::setErrors('same', 'Vous avez saisi le même mot de passe');
    } else
        BuildErrors::setErrors('password', 'Mot de passe incorrect');
}

// Change the fullname
if (isset($_POST['change-fullname'], $_POST['fullname'])) {
    $user->setFullname($_POST['fullname'])
        ->setRoles($user->getCollectionsRoles());
    $errors = BuildErrors::getErrors();
    if (empty($errors)) {
        $repository->edit($user);
        $_POST = [];
        $_SESSION['success'] = true;
        $_SESSION['content'] = 'Vous avez modifié (e) votre nom';
    }
}

// Edit profile picture
if (isset($_POST['add-picture'], $_FILES['image']['name'])) {
    $name = replace($user->getPseudo()) . rand(10, 550);
    $path = uploadFile($_FILES['image'], $name, '2097152', '../public/images/UserProfile/');
    $errors = BuildErrors::getErrors();
    if (empty($errors) and move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
        if ($user->getProfile() != null) {
            unlink('../public/' . $user->getProfile());
            $user->setProfile(str_replace('../public/', '', $path));
            $repository->addProfilePicture($user);
            $_POST = [];
            $_SESSION['success'] = true;
            $_SESSION['content'] = 'Photo de profil modifiée avec succès';
        } else {
            $user->setProfile(str_replace('../public/', '', $path));
            $repository->addProfilePicture($user);
            $_POST = [];
            $_SESSION['success'] = true;
            $_SESSION['content'] = 'Photo de profil modifiée avec succès';
        }
    } else
        BuildErrors::setErrors('move_pic', "Impossible de télécharger l'image");
}

$errors = BuildErrors::getErrors();

?>

<div class="page-content">
    <div class="container">
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <?= messageFlash('warning', $err) ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['success']) && $_SESSION['success'] == true) {
        echo messageFlash('success', $_SESSION['content']);
        $_SESSION['content'] = '';
        $_SESSION['success'] = false;
    }
    ?>
    </div>
    <div class="row ps-5 mt-3 d-flex flex-wrap g-4 align-items-center">
        <div class="col-xl-5 col-md-5">
            <div class="box">
                <?php if ($user->getProfile() != null): ?>
                    <img src="<?= BASE_URL . $user->getProfile() ?>" alt="man image">
                <?php else: ?>
                    <img src="<?= LtAvatar::initialAvatar(unClean($user->getFullname())) ?>" alt="man image">
                <?php endif ?>
                <div class="con">
                    <h1 class="mt-2"><?= excerpt(unClean($user->getFullname()), 16) ?></h1>
                    <p><?= unClean($user->getPseudo()) ?></p>
                    <p><?= unClean($user->getEmail()) ?></p>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="wrapper-profile">
                <form method="post" class="form-profile">
                    <div class="input-field">
                        <input type="password" id="old-pass" name="old-pass" value="<?= $_POST['old-pass'] ?? '' ?>"
                            required>
                        <label>Ancien mot de passe</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="new-pass" class="password-v" value="<?= $_POST['new-pass'] ?? '' ?>"
                            id="new-pass" required>
                        <label>Nouveau mot de passe</label>
                        <div class="view">
                            <i class="fas fa-eye"></i>
                            <i class="fas fa-eye-slash"></i>
                        </div>
                    </div>
                    <p class="r-error" style="color: var(--col-red); font-size: var(--font-p);"></p>
                    <button type="submit" name="change-password">Changer</button>
                </form>
            </div>
        </div>
    </div>
    <div class="mt-5">
        <div class="card-box profile p-3">
            <div class="rio-card">
                <div>
                    <div class="numbers"><?= $count_dislike ?></div>
                    <div class="card-name">Nombre de dislikes</div>
                </div>
                <div class="icon-bx">
                    <i class="fas fa-book-open-reader"></i>
                </div>
            </div>
            <div class="rio-card">
                <div>
                    <div class="numbers"><?= $count_like ?></div>
                    <div class="card-name">Nombre de likes</div>
                </div>
                <div class="icon-bx">
                    <i class="far fa-thumbs-up"></i>
                </div>
            </div>
            <div class="rio-card">
                <div>
                    <div class="numbers">4</div>
                    <div class="card-name">Commentaires éffectués</div>
                </div>
                <div class="icon-bx">
                    <i class="far fa-comments"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-5 row ps-5 d-flex flex-wrap g-4 align-items-center">
        <div class="col-xl-6 col-md-6">
            <div class="wrapper-profile">
                <form method="post" class="form-profile">
                    <h2>Changer votre nom</h2>
                    <div class="input-field">
                        <input type="text" name="fullname" required>
                        <label>Votre nom complet</label>
                    </div>
                    <button type="submit" name="change-fullname">Changer</button>
                </form>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="wrapper-profile">
                <form method="post" class="form-profile" enctype="multipart/form-data">
                    <h2>Modifier mon avatar</h2>
                    <div class="input-field">
                        <input type="file" name="image" required>
                    </div>
                    <button type="submit" name="add-picture">Modifier</button>
                </form>
            </div>
        </div>
    </div>
</div>