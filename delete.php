<?php
// inclusion de la bibliothèque des fonctions books
require "books-functions.php";

// Récupération du paramètre id
$id = filter_input(INPUT_GET, "id");

// Récupération la liste des livres
$bookList = getData();



// filtrer la liste des livres
// pour ne conserver que ceux dont l'id est différent
// du paramètre
$bookList = deleteOneById($bookList, $id);



saveToJsonFile($bookList);


// Redirection vers index.php
redirectTo("index.php");