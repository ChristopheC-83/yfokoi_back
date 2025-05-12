<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom border-black shadowCustom mb-5">
    <div class="container-fluid container">
        <a class="navbar-brand" href="<?= ROOT ?>">PHP / MVC / POO</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= ROOT ?>"><button class="btn btn-light">Accueil</button></a>
                </li>

                <?php if (empty($_SESSION['name'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT ?>account/connection" class=""><button class="btn btn-light">Connexion</button></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT ?>account/register" class=""><button class="btn btn-light">Inscription</button></a>
                    </li>
                <?php else : ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT ?>account/profile" class=""><button class="btn btn-light"><?= $_SESSION['name'] ?></button></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT ?>account/logout" class=""><button class="btn btn-light">Déconnexion</button></a>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>