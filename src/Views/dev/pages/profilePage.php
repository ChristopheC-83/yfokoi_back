<h1 class="text-center text-decoration-underline">Mon Profil :</h1>

<div class="col-12 col-md-9 col-lg-6 ">
    <p>Nom : <?= $_SESSION['name'] ?></p>
    <p>Email : <?= $_SESSION['email'] ?></p>
    <p>Rôle : <?= $_SESSION['role'] ?></p>
</div>