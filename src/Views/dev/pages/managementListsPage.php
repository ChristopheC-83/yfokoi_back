<h1 class="text-center text-decoration-underline mb-5">Gestion du partage de mes listes</h1>
<hr class="my-3">

<h2>Choix de la liste à gérer :</h2>
<form method="POST" class="w-50" action="<?= ROOT ?>managementLists/myLists">
    <select class="form-select" onchange="submit()" name="id_list">
        <?php foreach ($allListOfUser as $list): ?>
            <option value="<?= $list['id'] ?>"
                <?= $list['id'] == $selected_list['id'] ? 'selected' : '' ?>>
                <?= $list['name'] ?> - <?= $list['id'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<!-- <?= dump($_SESSION)  ?> -->

<hr class="my-3">
<h2>Liste <i><?= $selected_list['name'] ?></i> partagée avec :</h2>
<?= dump($usersSharingThisList) ?>




<hr class="my-3">
<h2>Nouveau partage avec :</h2>
<form method="POST" class="w-100" action="<?= ROOT ?>managementLists/shareList">
    <div class="d-flex w-100 gap-3">
        <input type="hidden" name="list_id" value="<?= $selected_list['id'] ?>">
        <input type="hidden" name="author_id" value="<?= $_SESSION['user_id'] ?>">
        <input type="hidden" name="author_name" value="<?= $_SESSION['name'] ?>">

        <select class="form-select w-25" name="user_id" >
            <option value="">Sélectionner un ami</option>
            <?php foreach ($usersNotSharingThisList as $friend): ?>
                <option value="<?= $friend['id'] ?>">
                    <?= $friend['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select class="form-select w-50" name="access_level"  >
            <option value="">Sélectionner un niveau de partage (modifiable)</option>
            <?php foreach ($accessLevels as $level): ?>
                <option value="<?= $level['id'] ?>" > <?= $level['level_id'] ?> - <?= $level['description'] ?></option>
            <?php endforeach; ?>
        </select>
                <button class="btn btn-secondary fs-4 p-1" >✅</button>
    </div>
</form>