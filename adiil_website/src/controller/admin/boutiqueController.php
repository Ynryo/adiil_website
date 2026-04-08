<?php

require_once 'src/model/bdd/article.php';
require_once 'src/model/utils/files_save.php';

class BoutiqueController
{
    public function show()
    {
        $articles = getAllArticles();

        $selectedArticle = null;
        if (isset($_GET['id'])) {
            $selectedArticle = getArticle((int)$_GET['id']);
        } elseif (!empty($articles)) {
            $selectedArticle = getArticle($articles[0]['id_article']);
        }

        include_once 'src/view/admin/panels/boutique.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=admin-admin/boutique');
            exit();
        }

        $id = (int)$_POST['id'];
        $name = trim($_POST['name']);
        $xp = (int)$_POST['xp'];
        $stocks = (int)$_POST['stocks'];
        $reduction = isset($_POST['reduction']) ? 1 : 0;
        $price = (float)$_POST['price'];
        $categorie = trim($_POST['categorie']);

        updateArticle($id, $name, $xp, $stocks, $reduction, $price, $categorie);

        header('Location: /?page=admin-admin/boutique&id=' . $id);
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=admin-admin/boutique');
            exit();
        }

        $id = (int)$_POST['id'];
        deleteArticle($id);

        header('Location: /?page=admin-admin/boutique');
        exit();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=admin-admin/boutique');
            exit();
        }

        $newId = createArticle();

        header('Location: /?page=admin-admin/boutique&id=' . $newId);
        exit();
    }

    public function uploadImage()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=admin-admin/boutique');
            exit();
        }

        $id = (int)$_POST['id'];
        $article = getArticle($id);

        if (!$article) {
            header('Location: /?page=admin-admin/boutique');
            exit();
        }

        $imageName = saveImage();

        if ($imageName) {
            if (!empty($article['image_article'])) {
                deleteFile($article['image_article']);
            }
            updateArticleImage($id, $imageName);
        }

        header('Location: /?page=admin-admin/boutique&id=' . $id);
        exit();
    }
}