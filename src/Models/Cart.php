namespace App\Models;

use App\Database\DB;

/**
* Classe de gestion du panier (session-based).
*/
class Cart
{
private DB $db;

public function __construct(DB $db)
{
$this->db = $db;

if (!isset($_SESSION)) {
session_start();
}

if (!isset($_SESSION['cart'])) {
$_SESSION['cart'] = [];
}
}

/**
* Ajouter un article au panier.
*/
public function add(int $id): void
{
if (isset($_SESSION['cart'][$id])) {
$_SESSION['cart'][$id]++;
} else {
$_SESSION['cart'][$id] = 1;
}
}

/**
* Supprimer un article du panier.
*/
public function remove(int $id): void
{
if (isset($_SESSION['cart'][$id])) {
unset($_SESSION['cart'][$id]);
}
}

/**
* Mettre à jour la quantité d'un article.
*/
public function update(int $id, int $quantity): void
{
if ($quantity <= 0) { $this->remove($id);
    } else {
    $_SESSION['cart'][$id] = $quantity;
    }
    }

    /**
    * Calculer le total du panier.
    */
    public function total(): float
    {
    $total = 0;
    if (empty($_SESSION['cart'])) {
    return $total;
    }

    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(",", array_fill(0, count($ids), "?"));
    $types = str_repeat("i", count($ids));

    $products = $this->db->select(
    "SELECT id_article, prix_article FROM ARTICLE WHERE id_article IN ($placeholders)",
    $types,
    $ids
    );

    foreach ($products as $product) {
    $qty = $_SESSION['cart'][$product['id_article']] ?? 0;
    $total += (float) $product['prix_article'] * $qty;
    }

    return $total;
    }

    /**
    * Compter le nombre total d'articles dans le panier.
    */
    public function count(): int
    {
    return array_sum($_SESSION['cart'] ?? []);
    }

    /**
    * Vider le panier.
    */
    public function clear(): void
    {
    $_SESSION['cart'] = [];
    }
    }