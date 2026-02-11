<?php

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once "src/model/database.php";

function execute($sqlFile) {
    $bdd = get_bdd();
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!file_exists($sqlFile)) {
        echo "❌ Fichier SQL introuvable : $sqlFile<br>";
        return false;
    }

    $sql = file_get_contents($sqlFile);

    if (!$sql || trim($sql) === "") {
        echo "❌ Le fichier SQL est vide : $sqlFile<br>";
        return false;
    }

    try {
        $bdd->exec($sql);
        echo "✔️ Script exécuté : $sqlFile<br>";
        return true;
    } catch (PDOException $e) {
        echo "❌ Erreur SQL dans $sqlFile : " . $e->getMessage() . "<br>";
        return false;
    }
}

function setHashedPassword() {
    $bdd = get_bdd();
    $query = $bdd->query("SELECT id_membre, password_membre FROM MEMBRE");
    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $bdd->prepare("UPDATE MEMBRE SET password_membre = :mdp WHERE id_membre = :id");

    foreach ($users as $user) {
        $mdp_hash = password_hash($user["password_membre"], PASSWORD_DEFAULT);

        $stmt->execute([
            ':mdp' => $mdp_hash,
            ':id' => $user["id_membre"]
        ]);
    }
}

function create_database() {
    if(!execute('assets/sql/creation.sql')) return;

    if(!execute('assets/sql/insertion.sql')) return;
    setHashedPassword();
}

create_database();