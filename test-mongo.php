<?php
require 'vendor/autoload.php';

$lignes = file('.env');
$uri = '';

foreach ($lignes as $ligne) {
    $ligne = trim($ligne);
    if (str_starts_with($ligne, 'MONGODB_URI=')) {
        $uri = substr($ligne, strlen('MONGODB_URI='));
    }
}

if ($uri === '') {
    echo 'URI manquante';
    exit;
}

try {
    $client = new MongoDB\Client($uri);
    $collection = $client->vite_et_gourmand->statistique_commande;

    $resultat = $collection->insertOne([
        'commande_id' => 1,
        'date' => date('Y-m-d'),
        'test' => true,
    ]);

    echo 'Document insere : ' . $resultat->getInsertedId();
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}