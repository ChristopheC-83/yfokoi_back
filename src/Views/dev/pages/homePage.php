<?php if (isset($_SESSION['name'])) : ?>

    <h1 class="text-center text-decoration-underline">Mes listes</h1>



    <form action="<?= ROOT ?>lists/newList" class="d-flex gap-3 mb-4" method="POST">
        <input type="hidden" name="owner_id" value="<?= $_SESSION['user_id'] ?>">
        <input type="text" class="form-control col-10 col-md-8 col-lg-6" id="name" name="name" placeholder="Nouvelle liste">
        <button class="btn btn-success">Créer</button>
    </form>


    <form action="<?= ROOT ?>lists/selectList" method="post" class="d-flex gap-3 mb-4">
        <select name="list_id" class="form-select col-10 col-md-8 col-lg-6" onchange="submit()">
            <option value="">Sélectionner une liste perso à afficher</option>
            <?php foreach ($listsOfUser as $list): ?>
                <option value="<?= $list['id'] ?>"
                    <?= ($list['id'] == ($_SESSION['selected_list_id'] ?? null)) ? 'selected' : '' ?>><?= $list['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <form action="<?= ROOT ?>lists/addItem" class="d-flex gap-3 mb-4" method="POST">

        <?php if (isset($_SESSION['selected_list_id'])): ?>
            <input type="hidden" name="selected_list_id" value="<?= $_SESSION['selected_list_id'] ?>">
        <?php endif ?>
        <input type="hidden" name="created_by" value="<?= $_SESSION['name'] ?>">
        <input type="text" class="form-control col-10 col-md-8 col-lg-6" id="content" name="content"
            placeholder="Ajouter à la liste" autofocus>
        <button class="btn btn-success">Ajouter</button>
    </form>

    <?php if (isset($_SESSION['selected_list_id'])): ?>
        <h2 class="text-center text-decoration-underline mt-5">Eléments de la liste : <?= $list_name ?></h2>
        <!-- <?= dump($items_list) ?> -->
        <?php foreach ($items_list as $item): ?>
            <div
                class="d-flex justify-content-between align-items-center border border-2 border-dark rounded-3 p-2 pb-0 mb-2 <?= $item['is_done'] == 1 ? 'bg-info' : '' ?>">
                <div class="d-flex gap-3">
                    <form class="d-flex gap-3 " method="POST" action="<?= ROOT ?>lists/itemIsDone" onchange="submit()">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <input type="checkbox" name="is_done" class="mb-1" <?= $item['is_done'] == 1 ? 'checked' : '' ?>>
                    </form>
                    <?php if (isset($_SESSION['edit_item_id']) && $_SESSION['edit_item_id'] == $item['id']): ?>
                        <!-- Formulaire de modification -->
                        <form action="<?= ROOT ?>lists/updateItem" method="POST" class="d-flex gap-2">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="text" name="new_content" class="form-control" value="<?= htmlspecialchars($item['content']) ?>">
                            <button class="btn btn-success">💾</button>
                        </form>
                        <form action="<?= ROOT ?>lists/exitEditMode" method="POST">
                            <button class="btn btn-warning">❌</button>
                        </form>
                    <?php else: ?>
                        <p class="mb-2 <?= $item['is_done'] == 1 ? 'text-decoration-line-through' : '' ?>">
                            <?= htmlspecialchars($item['content']) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="d-flex gap-3">
                    <form action="<?= ROOT ?>lists/editMode" method="POST" class="pb-2">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button class="fs-3">✏️</button>
                    </form>
                    <form action="<?= ROOT ?>lists/deleteItem" method="post" class="pb-2">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button class="fs-3">🚮</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <form action="<?= ROOT ?>lists/deleteAllDone" method="POST" class="d-flex justify-content-center mt-4">
            <input type="hidden" name="id_list" value="<?= $_SESSION['selected_list_id'] ?>">
            <button class="btn btn-info">
                <p class="fs-3 bold align-center mb-2">Supprimer tous les éléments cochés</p>
            </button>
        </form>
    <?php endif ?>
<?php else : ?>
    <h1 class="text-center text-decoration-underline">Bienvenue sur votre outil YFOKOI !</h1>
    <p class="text-center">Connectez-vous pour accéder à vos listes.</p>

<?php endif ?>