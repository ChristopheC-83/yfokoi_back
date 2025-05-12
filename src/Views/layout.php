<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="description" content="<?= $description ?>">
    <title><?= $title ?></title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require_once(BASE_DIR . "/src/Views/components/header.php")  ?>

    <!-- pour afficher les messagess -->
    <?php if (!empty($_SESSION['alert'])) : ?>
        <div class="container mt-3 alert <?= $_SESSION['alert']['type'] ?>" role="alert">
            <?= $_SESSION['alert']['message'] ?>
        </div>

    <?php
        unset($_SESSION['alert']);
    endif;
    ?>

    <!-- <?php if (!empty($_SESSION['name'])) {
        dump($_SESSION);
    } ?> -->




    <div class="flex-grow-1 container">
        <div id="formAlert" class="alert d-none mt-3 " role="alert"></div>
        <?= $content ?>
    </div>



    <?php require_once(BASE_DIR . "/src/Views/components/footer.php")  ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= ROOT ?>public/javascript/main.js"></script>
    <?php if (!empty($allJS)) : ?>
        <?php foreach ($allJS as $script) : ?>
            <script src="<?= ROOT ?>public/javascript/<?= $script ?>" type="module"></script>
        <?php endforeach; ?>
    <?php endif; ?>

</body>

</html>