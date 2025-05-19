<h1 class="text-center text-decoration-underline">Gestion du partage de mes listes</h1>
<hr class="my-3">
<!-- List  = <?= dump($selected_list) ?> -->
<!-- allListOfUser  = <?= dump($allListOfUser) ?>  -->
<!-- allListOfUser  = <?= dump($myFriends) ?>  -->

<h2>Choix de la liste à gérer :</h2>
<form action="<?= ROOT ?>managementLists/myLists" method="GET" class="w-50">
    <select class="form-select" onchange="location.href = '<?= ROOT ?>managementLists/myLists/' + this.value">
        <option value="">Sélectionner une liste à gérer</option>
        <?php foreach ($allListOfUser as $list): ?>
            <option value="<?= $list['id'] ?>"
                <?= ($list['id'] == ($selected_list['id'] ?? null)) ? 'selected' : '' ?>><?= $list['name'] . "-" . $list['id'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>
<hr class="my-3">
<h2>Je souhaite la partager avec :</h2>
<?= dump($levels)  ?>
<form action="<?= ROOT ?>managementLists/modifyListAccess" method="post" class="w-100 d-flex gap-3">
    <input type="hidden" name="list_id" value="<?= $selected_list['id'] ?>">
    <select name="user_id" id="user_id" class="form-select w-25">
        <option value="">Sélectionner un ami</option>
        <?php foreach ($myFriends as $myFriend): ?>
            <option value="<?= $myFriend['id'] ?>"><?= $myFriend['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <select name="access_level" id="access_level" class="form-select w-50">
        <?php foreach ($levels as  $key => $level): ?>
            <option value="<?= $key ?>"><?= $level['label'] ?></option>
        <?php endforeach; ?>
    </select>    
    <button class="btn btn-success col-2">Ajouter</button>
</form>