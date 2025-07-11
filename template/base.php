<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name="description" content="<?= $pg_desc ?? "RioToon | Lecture et Téléchargement de Webtoon" ?>">
    <meta name="keywords" content="Webtoons, Webtoon VF, Webtoon Gratuit, Télécharger Webtoon, Lire Webtoon">
    <title><?= $pg_title ?? 'RioToon | Lecture et Téléchargement de Webtoon' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/css/scolor.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/css/navbar.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= BASE_URL ?>styles/fontawesome/css/all.min.css">
    
</head>
<body>

<?php
if (isset($is_admin) && $is_admin == true)
    require_once '_partials/navbar-admin.php';
else
    require_once '_partials/navbar.php';
?>
    <div class="container-lg main">
        <?= $pg_content?>
    </div>
    <?php
    if (isset($is_admin) && $is_admin == true) {
        echo '</div>'; // Closing of the grid-container div
        require_once '_partials/footer-admin.php';
    } else
        require_once '_partials/footer.php';
    ?>
    <script src="<?= BASE_URL ?>styles/js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="<?= BASE_URL; ?>styles/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>styles/js/main.js"></script>
</body>
</html>
