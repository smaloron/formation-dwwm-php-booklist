<?php
// Récupération du paramètre id
$id = filter_input(INPUT_GET, "id");

// Récupération la liste des livres

// le nom du fichier a lire
$fileName = "books.json";
// lecture du fichier
$data = file_get_contents($fileName);
// conversion du contenu du fichier en tableau
$bookList = json_decode($data, true);


// filtrer la liste des livres
// pour ne conserver que ceux dont l'id est différent
// du paramètre
$bookList = array_filter($bookList, function($item) use ($id){
    return $item["id"] != $id;
});

// Sauvegarder la liste dans le fichier json
// Conversion du tableau $bookList en json
$bookListSerialized = json_encode($bookList);

// Ecriture du contenu dans le fichier json
file_put_contents($fileName, $bookListSerialized);


// Redirection vers index.php
header("location:index.php");