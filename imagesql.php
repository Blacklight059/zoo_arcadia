<?php

// Chemin vers le répertoire contenant vos images
$directory = 'sql/img/Services/';

// Liste des fichiers dans le répertoire
$files = scandir($directory);

// Initialisation de la chaîne pour stocker les requêtes SQL
$sql = '';

// Parcourir chaque fichier
foreach ($files as $file) {
    // Vérifier si c'est un fichier image
    if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
        // Lire le contenu de l'image
        $imageData = file_get_contents($directory . $file);
        // Encoder l'image en base64
        $base64ImageData = base64_encode($imageData);
        // Ajouter le chemin d'accès à l'image encodée à la requête SQL
        $sql .= "INSERT INTO image (name, data) VALUES ('".$file."', '".$base64ImageData."');\n";
    }
}

// Afficher la requête SQL
echo $sql;

?>
