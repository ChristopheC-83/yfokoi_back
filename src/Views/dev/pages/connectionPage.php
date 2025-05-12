<h1 class="text-center text-decoration-underline">Connexion</h1>

<form class="container col-12 col-md-9 col-lg-6" method="POST" 
action="<?= ROOT ?>account/connectionAccount" id="connectionForm">
    <div class="mb-3">
        <label for="name_email" class="form-label">Ton nom/pseudo OU email</label>
        <input type="text" class="form-control" id="name_email" name="name_email">
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>


    <button type="submit" class="btn btn-primary">Se Connecter</button>
</form>