<h1 class="text-center text-decoration-underline">Mon Profil :</h1>
<?php if (isset($asksFriends) && !empty($asksFriends)): ?>
    <hr>
    <h2 class="text-decoration-underline mb-3">Mes demandes d'ajout</h2>
    <?php foreach ($asksFriends as $ask): ?>
        <div class="col-12 col-md-9  mb-4 border border-black border-2 rounded-3 px-2 py-1 d-flex align-items-center gap-3 mb-3 justify-content-between">
            <div class="d-flex flex-column">
                <p class="mb-0 w-50 fs-3"><?= $ask['name'] ?></p>
                <p class="mb-0 w-50 text-secondary"><?= $ask['email'] ?></p>
            </div>
            <div class="d-flex gap-3">
                <form method="post" action="<?= ROOT ?>usersLinks/validateAskFriendRequest"
                    onSubmit="return confirm('Vous validez la mise en lien avec <?= $ask['name'] ?>')">
                    <input type="hidden" name="idContact" value="<?= $ask['id'] ?>">
                    <input type="hidden" name="response" value="1">
                    <button class="btn btn-success">
                        Accepter la demande</button>
                </form>
                <form method="post" action="<?= ROOT ?>usersLinks/validateAskFriendRequest"
                    onSubmit="return confirm('Vos refusez la mise en lien avec <?= $ask['name'] ?>')">
                    <input type="hidden" name="idContact" value="<?= $ask['id'] ?>">
                    <input type="hidden" name="response" value="0">
                    <button class="btn btn-danger">
                        Refuser la demande</button>
                </form>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>

<hr>
<div class="col-12 col-md-9 col-lg-6 mb-4">
    <p>Nom : <?= $_SESSION['name'] ?></p>
    <p>Email : <?= $_SESSION['email'] ?></p>
    <p>Rôle : <?= $_SESSION['role'] ?></p>
    <form method="post" action="<?= ROOT ?>account/deleteAccount"
        onSubmit="return confirm('Action irréversible, on supprime ?')">
        <button class="btn btn-danger">
            Supprimer le compte</button>
    </form>
</div>

<div class="col-12 col-md-9 col-lg-6 ">

    <form action="<?= ROOT ?>usersLinks/searchContact" class="d-flex gap-3 mb-4" method="POST">
        <input type="text" placeholder="pseudo recherché" name="nameSearched">
        <button class="btn btn-success">Rechercher</button>
    </form>



</div>