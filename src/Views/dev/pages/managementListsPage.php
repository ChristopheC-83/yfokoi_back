<h1 class="text-center text-decoration-underline mb-5">Gestion du partage de mes listes</h1>
<hr class="my-3">

<h2>Choix de la liste à gérer :</h2>
<form method="GET" class="w-50" action="<?= ROOT ?>managementLists/myLists">
    <select class="form-select" >
        <?php foreach ($allListOfUser as $list): ?>
            <option value="<?= $list['id'] ?>" name="id_list"
            <?=   $list['id'] == $selected_list['id'] ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($list['name']) ?> - <?= $list['id'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<hr class="my-3">

