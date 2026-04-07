<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/public/styles/shop_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">
</head>

<body class="body_margin">

    <?php
    require_once "header.php";
    require_once 'cart_class.php';

    use App\Database\DB;
    $db = DB::getInstance();
    $cart = new cart($db);

    // --- Gestion de la recherche, des filtres et tris ---
    
    $filters = [];
    $orderBy = "name_asc";
    $searchTerm = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['reset'])) {
            $filters = [];
            $orderBy = "name_asc";
            $searchTerm = "";
        } else {
            if (isset($_POST['category'])) {
                $filters = $_POST['category'];
            }
            if (isset($_POST['sort'])) {
                $orderBy = $_POST['sort'];
            }
            if (!empty($_POST['search'])) {
                $searchTerm = $_POST['search'];
            }
        }
    }

    // Construction de la requête SQL
    $query = "SELECT * FROM ARTICLE";
    $whereClauses = ["deleted = false"];
    $params = [];

    if (!empty($searchTerm)) {
        $whereClauses[] = "nom_article LIKE ?";
        $params[] = '%' . $searchTerm . '%';
    }

    if (!empty($filters)) {
        $placeholders = implode(", ", array_fill(0, count($filters), "?"));
        $whereClauses[] = "categorie_article IN ($placeholders)";
        $params = array_merge($params, $filters);
    }

    if (!empty($whereClauses)) {
        $query .= " WHERE " . implode(" AND ", $whereClauses);
    }

    // Tri
    $sortMap = [
        'price_asc' => 'prix_article ASC',
        'price_desc' => 'prix_article DESC',
        'name_asc' => 'nom_article ASC',
        'name_desc' => 'nom_article DESC',
    ];
    $query .= " ORDER BY " . ($sortMap[$orderBy] ?? 'nom_article ASC');

    $products = $db->select($query, str_repeat("s", count($params)), $params);
    ?>

    <h1>LA BOUTIQUE</h1>

    <div id="principal-section">
        <form method="post" id="filter-form">
            <fieldset>
                <input id="search-input" type="text" name="search" placeholder="Rechercher un article"
                    value="<?= htmlspecialchars($searchTerm) ?>">
            </fieldset>
            <details>
                <summary>Catégories</summary>
                <fieldset>
                    <?php
                    $categories = ['Sucré', 'Salé', 'Boisson', 'Merch'];
                    foreach ($categories as $cat):
                        ?>
                        <label><input type="checkbox" name="category[]" value="<?= htmlspecialchars($cat) ?>"
                                <?= in_array($cat, $filters) ? 'checked' : '' ?>> <?= htmlspecialchars($cat) ?></label><br>
                    <?php endforeach; ?>
                </fieldset>
            </details>
            <div>
                <label for="sort">Trier par</label>
                <select name="sort" id="sort">
                    <option value="name_asc" <?= $orderBy === 'name_asc' ? 'selected' : '' ?>>Ordre alphabétique (A-Z)
                    </option>
                    <option value="name_desc" <?= $orderBy === 'name_desc' ? 'selected' : '' ?>>Ordre anti-alphabétique
                        (Z-A)</option>
                    <option value="price_asc" <?= $orderBy === 'price_asc' ? 'selected' : '' ?>>Prix croissant</option>
                    <option value="price_desc" <?= $orderBy === 'price_desc' ? 'selected' : '' ?>>Prix décroissant</option>
                </select>
            </div>
            <button type="submit" name="reset">Réinitialiser</button>
        </form>

        <div id="cart-info">
            <button>
                <a href="cart.php">
                    <img src="/public/assets/logo_caddie.png" alt="Logo du panier">
                    <p>Panier (<span id="count"><?= $cart->count() ?></span>)</p>
                </a>
            </button>
        </div>
    </div>

    <p id="message-reduc">
        * Articles non éligibles aux réductions de grade
    </p>

    <?php if (!empty($products)): ?>
        <div id="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div>
                        <?php if ($product['image_article'] === null): ?>
                            <img src="/admin/ressources/default_images/boutique.png" alt="Image de l'article" />
                        <?php else: ?>
                            <img src="/api/files/<?= htmlspecialchars($product['image_article']) ?>" alt="Image de l'article" />
                        <?php endif ?>
                        <h3 title="<?= htmlspecialchars($product['nom_article']) ?>">
                            <?= htmlspecialchars($product['nom_article']) ?>
                        </h3>
                        <p><?= number_format((float) $product['prix_article'], 2, ',', ' ') ?> €</p>
                        <p><?= (int) $product['xp_article'] ?> XP
                            <?php if (!(int) $product['reduction_article']): ?>
                                <span> * </span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <p class="stock-status">
                            <?php if ((int) $product['stock_article'] !== 0): ?>
                                <a class="addCart" href="/cart_add.php?id=<?= (int) $product['id_article'] ?>">
                                    Ajouter au panier
                                </a>
                            <?php else: ?>
                                <button class="out-of-stock" disabled>Épuisé</button>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucun produit trouvé pour les critères sélectionnés.</p>
    <?php endif; ?>

    <?php require_once "footer.php" ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector("#filter-form");

            // Soumission sur Entrée dans le champ de recherche
            const searchInput = document.querySelector("input[name='search']");
            searchInput.addEventListener("keydown", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    form.submit();
                }
            });

            // Soumission sur changement de catégorie
            const detailsElement = document.querySelector("details");
            if (sessionStorage.getItem("details-open") === "true") {
                detailsElement.open = true;
            }
            const categoryCheckboxes = document.querySelectorAll("input[name='category[]']");
            categoryCheckboxes.forEach(function (checkbox) {
                checkbox.addEventListener("change", function () {
                    sessionStorage.setItem("details-open", "true");
                    form.submit();
                });
            });
            detailsElement.addEventListener("toggle", function () {
                if (!detailsElement.open) {
                    sessionStorage.removeItem("details-open");
                }
            });

            // Soumission sur changement de tri
            const sortSelect = document.querySelector("select[name='sort']");
            sortSelect.addEventListener("change", function () {
                form.submit();
            });
        });
    </script>

    <script>
        // Vanilla JS pour l'ajout au panier (remplace jQuery 1.7.2)
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".addCart").forEach(function (link) {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    const url = this.getAttribute("href");

                    fetch(url)
                        .then(function (response) {
                            if (response.ok) {
                                const countEl = document.getElementById("count");
                                if (countEl) {
                                    countEl.textContent = parseInt(countEl.textContent) + 1;
                                }
                            }
                        })
                        .catch(function (err) {
                            console.error("Erreur ajout panier:", err);
                        });
                });
            });
        });
    </script>

</body>

</html>