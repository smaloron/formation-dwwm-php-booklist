<?php
// le nom du fichier a lire
define("FILE_NAME", "books.json");

/*
* Lecture du fichier json
* et conversion et tableau associatif
*/
function getData(){
    // lecture du fichier
    $data = file_get_contents(FILE_NAME);
    // conversion du contenu du fichier en tableau
    $bookList = json_decode($data, true);

    return $bookList;
}

/*
* Suppression d'un livre
*/
function deleteOneById(array $bookList, string $id){
    $bookList = array_filter($bookList, function ($item) use ($id) {
        return $item["id"] != $id;
    });
    
    return $bookList;
}

// Sauvegarder la liste dans le fichier json
function saveToJsonFile($bookList){
    // Conversion du tableau $bookList en json
    $bookListSerialized = json_encode($bookList);

    // Ecriture du contenu dans le fichier json
    file_put_contents(FILE_NAME, $bookListSerialized);
}

// Redirection vers un autre fichier
function redirectTo($target){
    header("location:$target");
    exit;
}
