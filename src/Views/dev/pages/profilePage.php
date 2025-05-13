<h1 class="text-center text-decoration-underline">Mon Profil :</h1>

<div class="col-12 col-md-9 col-lg-6 mb-4">
    <p>Nom : <?= $_SESSION['name'] ?></p>
    <p>Email : <?= $_SESSION['email'] ?></p>
    <p>Rôle : <?= $_SESSION['role'] ?></p>
    <form method="post" action="<?= ROOT ?>account/deleteAccount" onSubmit="return confirm('Action irréversible, on supprime ?')">
        <button class="btn btn-danger" >
            Supprimer le compte</button>
    </form>
</div>

<div class="col-12 col-md-9 col-lg-6 ">

    <form action="<?= ROOT ?>usersLinks/searchContact" class="d-flex gap-3 mb-4" method="POST">
        <input type="text" placeholder="pseudo recherché" name="nameSearched">
        <button class="btn btn-success">Rechercher</button>
    </form>



</div>