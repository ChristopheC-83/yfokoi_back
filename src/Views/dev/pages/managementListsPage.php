<h1 class="text-center text-decoration-underline mb-5">Gestion du partage de mes listes</h1>
<hr class="my-3">

<h2>Choix de la liste à gérer :</h2>
<form method="POST" class="w-50" action="<?= ROOT ?>managementLists/myLists">
    <select class="form-select" onchange="submit()" name="id_list">
        <?php foreach ($allListOfUser as $list): ?>
            <option value="<?= $list['id'] ?>"
                <?= $list['id'] == $selected_list['id'] ? 'selected' : '' ?>>
                <?= $list['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<!-- <?= dump($_SESSION)  ?> -->

<?php if (!empty($usersSharingThisList)):  ?>
    <hr class="my-3">
    <h2>Liste <i><?= $selected_list['name'] ?></i> partagée avec :</h2>

    <?php foreach ($usersSharingThisList as $userSharing): ?>
        <div class="d-flex w-100 gap-3 align-items-center mb-3">
            <p class="form-control w-25 m-0"><?= $userSharing['user_name'] ?></p>
            <p class="m-0">qui peut</p>
            <form action="<?= ROOT ?>managementLists/modifyListAccess" method="POST">
                <input type="hidden" name="list_id" value="<?= $selected_list['id'] ?>">
                <input type="hidden" name="user_id" value="<?= $userSharing['user_id'] ?>">
                <input type="hidden" name="author_id" value="<?= $_SESSION['user_id'] ?>">
                <input type="hidden" name="author_name" value="<?= $_SESSION['name'] ?>">
                
                <select class="form-select  m-0" name="access_level" onchange="submit()">
                    <?php foreach ($accessLevels as $level): ?>
                        <option value="<?= $level['id'] ?>" 
                            <?= $level['id'] == $userSharing['access_level'] ? "selected" : "" ?>><?= $level['description'] ?></option>
                    <?php endforeach; ?>
                    <option value="-1">supprimer le partage</option>
                </select>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif ?>




<!-- <?= dump($usersSharingThisList) ?>
<?= dump($usersNotSharingThisList) ?> -->




<?php if (!empty($usersNotSharingThisList)) :  ?>

    <hr class="my-3">
    <h2>Nouveau partage avec :</h2>
    <form method="POST" class="w-100" action="<?= ROOT ?>managementLists/shareList">
        <div class="d-flex w-100 gap-3">
            <input type="hidden" name="list_id" value="<?= $selected_list['id'] ?>">
            <input type="hidden" name="author_id" value="<?= $_SESSION['user_id'] ?>">
            <input type="hidden" name="author_name" value="<?= $_SESSION['name'] ?>">

            <select class="form-select w-25" name="user_id">
                <option value="">Sélectionner un ami</option>
                <?php foreach ($usersNotSharingThisList as $friend): ?>
                    <option value="<?= $friend['id'] ?>">
                        <?= $friend['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select class="form-select w-50" name="access_level">
                <option value="">Sélectionner un niveau de partage (modifiable)</option>
                <?php foreach ($accessLevels as $level): ?>
                    <option value="<?= $level['id'] ?>"> <?= $level['level_id'] ?> - <?= $level['description'] ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-secondary fs-4 p-1">✅</button>
        </div>
    </form>

<?php endif ?>