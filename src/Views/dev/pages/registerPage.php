<h1 class="text-center text-decoration-underline">Inscription</h1>

<form class="container col-12 col-md-9 col-lg-6" method="POST" action="<?= ROOT ?>account/validateAndCreateAccount" id="registerForm">
    <div class="mb-3">
        <label for="name" class="form-label">Ton nom/pseudo</label>
        <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Ton email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="mb-3">
        <label for="confirmPassword" class="form-label">Vérification Mot de Passe</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
    </div>

    <button type="submit" class="btn btn-primary">Valider l'inscription</button>
</form>