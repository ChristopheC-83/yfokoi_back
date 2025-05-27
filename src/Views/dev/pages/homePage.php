<?= dump($list_selected) ?>

<?php if (!empty($_SESSION['name'])) : ?>

    <h1 class="text-center text-decoration-underline">Mes listes</h1>

    <form action="<?= ROOT ?>lists/newList" class="d-flex gap-3 mb-4" method="POST">
        <input type="hidden" name="owner_id" value="<?= $_SESSION['user_id'] ?>">
        <input type="text" class="form-control w-75" id="name" name="name" placeholder="Nouvelle liste">
        <button class="btn btn-success col-2">Créer</button>
    </form>

    <div class="d-flex mb-4 gap-3">
        <form action="<?= ROOT ?>lists/selectList" method="POST" class=" w-75">
            <select name="list_id" class="form-select" onchange="submit()">
                <option value="">Sélectionner une liste perso à afficher</option>
                <?php foreach ($myLists as $list): ?>
                    <option value="<?= $list['id'] ?>"
                        <?= ($list['id'] == ($context['selected_list_id'] ?? null)) ? 'selected' : '' ?>><?= $list['id'] . " - " . $list['name'] ?>
                    </option>
                <?php endforeach; ?>
                <?php foreach ($sharedLists as $list): ?>
                    <option value="<?= $list['list_id'] ?>"
                        <?= ($list['list_id'] == ($context['selected_list_id'] ?? null)) ? 'selected' : '' ?>>
                        <?= $list['list_id'] . " - " . $list['name'] ?> de <?= $list['author_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php if (isset($context['selected_list_id'])): ?>
            <div class="d-flex gap-4 align-items-center ms-4">
                <?php if ($context['favorite_list_id'] !== $context['selected_list_id']) : ?>
                    <form action="<?= ROOT ?>userContext/favoriteList" class="" method="POST">
                        <input type="hidden" name="id_list" value="<?= $context['selected_list_id'] ?>">
                        <button class="btn btn-info ">🤍</button>
                    </form>
                <?php else: ?>
                    <form action="<?= ROOT ?>userContext/unsetFavoriteList" class="" method="POST">
                        <button class="btn btn-info ">❤️</button>
                    </form>
                <?php endif ?>
                <?php if ($_SESSION['user_id'] == $list_selected['owner_id']): ?>
                    <form action="<?= ROOT ?>managementLists/myLists" method="post">
                        <input type="hidden" name="id_list" value="<?= $context['selected_list_id'] ?>">
                        <button class="btn btn-secondary">
                            <i class="fa-solid fa-share-nodes fa-lg"></i>
                        </button>
                    </form>
                    <form action="<?= ROOT ?>lists/deleteList" class="" onSubmit="return confirm('On supprime cette liste ?')" method="post">
                        <input type="hidden" name="id_list" value="<?= $context['selected_list_id'] ?>">
                        <input type="hidden" name="owner_id" value="<?= $_SESSION['user_id'] ?>">
                        <button class="btn btn-danger"><i class="fa-solid fa-trash-can fa-lg"></i></button>
                    </form>
                <?php endif ?>
            </div>
        <?php endif ?>
    </div>
    <?php if ($accessLevel == null || $accessLevel['access_level'] == 4  ||  $accessLevel['access_level'] == 3  || $accessLevel['access_level'] == 2): ?>
        <form action="<?= ROOT ?>items/addItem" class="d-flex gap-3 mb-4" method="POST">

            <?php if (isset($context['selected_list_id'])): ?>
                <input type="hidden" name="selected_list_id" value="<?= $context['selected_list_id'] ?>">
            <?php endif ?>
            <input type="hidden" name="created_by" value="<?= $_SESSION['name'] ?>">
            <input type="text" class="form-control w-75" id="content" name="content" placeholder="Ajouter à la liste" autofocus>
            <button class="btn btn-success col-2">Ajouter</button>
        </form>
    <?php endif; ?>

    <?php if (isset($context['selected_list_id'])): ?>
        <h2 class="text-center text-decoration-underline mt-5">Eléments de la liste : </h2>
        <!-- <?= dump($items_list) ?> -->
        <?php foreach ($items_list as $item): ?>
            <div
                class="d-flex justify-content-between align-items-center border border-2 border-dark rounded-3 p-2 pb-0 mb-2 <?= $item['is_done'] == 1 ? 'bg-info' : '' ?>">
                <div class="d-flex gap-3">
                    <form class="d-flex gap-3 " method="POST" action="<?= ROOT ?>items/itemIsDone" onchange="submit()">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <input type="checkbox" name="is_done" class="mb-1" <?= $item['is_done'] == 1 ? 'checked' : '' ?>>
                    </form>
                    <?php if (isset($_SESSION['edit_item_id']) && $_SESSION['edit_item_id'] == $item['id']): ?>
                        <!-- Formulaire de modification -->
                        <form action="<?= ROOT ?>items/updateItem" method="POST" class="d-flex gap-2">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="text" name="new_content" class="form-control"
                                value="<?= htmlspecialchars($item['content']) ?>">
                            <button class="btn btn-success">💾</button>
                        </form>
                        <form action="<?= ROOT ?>items/exitEditMode" method="POST">
                            <button class="btn btn-warning">❌</button>
                        </form>
                    <?php else: ?>
                        <?php
                        $doneClass = $item['is_done'] ? 'text-decoration-line-through' : '';
                        $creator = $item['created_by'] !== $list_selected['owner_id'] ? " - " . htmlspecialchars($item['creator_name']) : '';
                        ?>

                        <p class="mb-2 <?= $doneClass ?>">
                            <?= htmlspecialchars($item['content']) . $creator ?>
                        </p>
                    <?php endif; ?>
                </div>
                <?php if ($accessLevel == null || ($accessLevel['access_level'] == 4  || (
                    $accessLevel['access_level'] == 3 && $_SESSION['user_id'] == $item['created_by']))): ?>
                    <div class="d-flex gap-3">
                        <form action="<?= ROOT ?>items/editMode" method="POST" class="pb-2">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button class="fs-3">✏️</button>
                        </form>
                        <form action="<?= ROOT ?>items/deleteItem" method="post" class="pb-2">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button class="fs-3">🚮</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if ($accessLevel == null || $accessLevel['access_level'] == 4) : ?>
            <?php if ($deleteAllDoneBtn): ?>
                <form action="<?= ROOT ?>items/deleteAllDone" method="POST" class="d-flex justify-content-center mt-4">
                    <input type="hidden" name="id_list" value="<?= $context['selected_list_id'] ?>">
                    <button class="btn btn-info">
                        <p class="fs-3 bold align-center mb-2">Supprimer tous les éléments cochés</p>
                    </button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif ?>
<?php else : ?>
    <h1 class="text-center text-decoration-underline">Bienvenue sur votre outil YFOKOI !</h1>
    <p class="text-center">Connectez-vous pour accéder à vos listes.</p>
<?php endif ?>