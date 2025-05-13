<h1 class="text-center text-decoration-underline">Mon Profil :</h1>
<!-- <?=  dump($_SESSION) ?> -->
<div class="col-12 col-md-9 col-lg-6 ">
    <p>Nom : <?= $_SESSION['name'] ?></p>
    <p>Email : <?= $_SESSION['email'] ?></p>
    <p>Rôle : <?= $_SESSION['role'] ?></p>
</div>

<div class="col-12 col-md-9 col-lg-6 ">

    <form action="<?= ROOT ?>usersLinks/searchContact" class="d-flex gap-3 mb-4" method="POST">
        <input type="text" placeholder="pseudo recherché" name="nameSearched">
        <button class="btn btn-success">Rechercher</button>
    </form>



</div>

<?php  if(isset($contacts) && !empty($contacts)) : ?>
    
    <?php foreach ($contacts as $contact): ?>
        <div class="d-flex  border border-2 border-dark rounded-3 p-2 mb-2">
            <div class="d-flex gap-3 justify-content-between align-items-center w-100">
                <p class="m-0 p-0">
                    <?= htmlspecialchars($contact['name']) ?>
                </p>
                <form action="<?= ROOT ?>usersLinks/addContact" method="post">
                    <input type="hidden" name="idContact" value="<?= htmlspecialchars($contact['id']) ?>">
                    <button class="btn btn-primary">Ajouter du contact</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
<?php   endif; ?>