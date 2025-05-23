<h1 class="text-center text-decoration-underline mb-5">Gestion du partage de mes listes</h1>
<hr class="my-3">

<h2>Choix de la liste à gérer :</h2>
<form method="GET" class="w-50">
    <select class="form-select" onchange="location.href='<?= ROOT ?>managementLists/myLists/' + this.value">
        <option value="" disabled <?= empty($selected_list) ? 'selected' : '' ?>>Sélectionner une liste à gérer</option>
        <?php foreach ($allListOfUser as $list): ?>
            <option value="<?= $list['id'] ?>" <?= ($selected_list['id'] ?? null) === $list['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($list['name']) ?> - <?= $list['id'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<hr class="my-3">

<?php if ($selected_list): ?>

    <?php if (!empty($sharedUsers)): ?>
        <h2>Cette liste est déjà partagée avec :</h2>
        <ul class="list-group mb-4">
            <?php foreach ($sharedUsers as $user): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?= htmlspecialchars($user['name']) ?></span>
                    <div class="d-flex gap-3">
                    <span class="badge bg-primary"><?= htmlspecialchars($user['permissions']) ?></span>
                        <form action="<?= ROOT ?>managementLists/deleteListAccess" method="POST">
                            <input type="hidden" name="list_id" value="<?= $selected_list['id'] ?>">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </div>
                    </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <h3>Aucun partage actuellement pour cette liste.</h3>
    <?php endif; ?>

    <hr class="my-3">

    <h2>Je souhaite la partager avec :</h2>
    <form action="<?= ROOT ?>managementLists/modifyListAccess" method="post" class="w-100 d-flex gap-3 flex-wrap align-items-end">
        <input type="hidden" name="list_id" value="<?= $selected_list['id'] ?>">

        <div class="flex-fill">
            <label for="user_id" class="form-label">Ami :</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="">Sélectionner un ami</option>
                <?php foreach ($myFriends as $friend): ?>
                    <option value="<?= $friend['id'] ?>"><?= htmlspecialchars($friend['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex-fill">
            <label for="access_level" class="form-label">Niveau d'accès :</label>
            <select name="access_level" id="access_level" class="form-select" required>
                <option value="">Sélectionner le niveau d'accès</option>
                <?php foreach ($levels as $key => $level): ?>
                    <?php if ($key !== ''): // Ignore l'option vide en affichage ?>
                    <option value="<?= $key ?>"><?= htmlspecialchars($level['label']) ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="btn btn-success">Ajouter / Modifier</button>
    </form>

<?php else: ?>
    <p class="mt-4">Veuillez d'abord sélectionner une liste à gérer.</p>
<?php endif; ?>
