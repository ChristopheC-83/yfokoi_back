<h1 class="text-center text-decoration-underline">Mon Profil :</h1>
<hr class="my-5">
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

    <form action="<?= ROOT ?>account/profile" class="d-flex gap-3 mb-4" method="POST">
        <input type="text" placeholder="pseudo recherché" name="nameSearched">
        <button class="btn btn-success">Rechercher</button>
    </form>

</div>


<!--  Les amis recherchés -->

<?php  if(isset($searchedContact) && !empty($searchedContact)) : ?>
    
    <?php foreach ($searchedContact as $contact): ?>
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



<!--  Les demandes d'ajout reçues de tiers -->
<?php if (isset($asksFriends) && !empty($asksFriends)): ?>
    <hr class="my-5">
    <h2 class="text-decoration-underline mb-3">Mes demandes d'ajout<span class="fs-6"> (à valider)</span></h2>
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

<!--  Les demandes d'ajout envoyées -->
<?php if (isset($pendingFriends) && !empty($pendingFriends)): ?>
    <hr class="my-5">
    <h2 class="text-decoration-underline mb-3">Mes demandes d'ajout envoyées<span class="fs-6"> (en attente)</span></h2>
    <?php foreach ($pendingFriends as $friend): ?>
         <div class="col-12 col-md-9  mb-4 border border-black border-2 rounded-3 px-2 py-3 d-flex align-items-center gap-3 mb-3 justify-content-between">
            <div class="d-flex flex-column">
                <p class="mb-0 w-50 fs-3"><?= $friend['name'] ?></p>
            </div>
            <div class="d-flex gap-3">
                <form method="post" action="<?= ROOT ?>usersLinks/deleteLink"
                    onSubmit="return confirm('Annuler la demande envoyée à <?= $friend['name'] ?>')">
                    <input type="hidden" name="idContact" value="<?= $friend['id'] ?>">
                    <button class="btn btn-danger">
                        Annuler la demande</button>
                </form>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>

<!--  Les liens acceptés (dans les 2 sens) -->
<?php if (isset($acceptedFriends) && !empty($acceptedFriends)): ?>
    <hr class="my-5">
    <!-- <?= dump($acceptedFriends); ?> -->
    <h2 class="text-decoration-underline mb-3">Mes amis<span class="fs-6"> (liens validés)</span></h2>
    <?php foreach ($acceptedFriends as $friend): ?>
        <div class="col-12 col-md-9  mb-4 border border-black border-2 rounded-3 px-2 py-1 d-flex align-items-center gap-3 mb-3 justify-content-between">
            <div class="d-flex flex-column">
                <p class="mb-0 w-50 fs-3"><?= $friend['name'] ?></p>
                <p class="mb-0 w-50 text-secondary"><?= $friend['email'] ?></p>
            </div>
            <div class="d-flex gap-3">
                <form method="post" action="<?= ROOT ?>usersLinks/deleteLink"
                    onSubmit="return confirm('Vous supprimez le lien avec <?= $friend['name'] ?>')">
                    <input type="hidden" name="idContact" value="<?= $friend['id'] ?>">
                    <button class="btn btn-danger">
                        Supprimer le lien</button>
                </form>
            </div>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <h2 class="text-decoration-underline mb-3">Aucun ami trouvé</h2>
<?php endif ?>