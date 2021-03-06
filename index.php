<?php
/**********************************************
 * Récupération des données depuis le fichier json
 ***********************************************/
// le nom du fichier a lire
$fileName = "books.json";
// lecture du fichier
$data = file_get_contents($fileName);
// conversion du contenu du fichier en tableau
$bookList = json_decode($data, true);

/*********************************
 * Traitement du formulaire
 * d'ajout de livre
 *********************************/
// vérification de l'envoi des données
$isPosted = filter_has_var(INPUT_POST, "submit");

// Tableau des erreurs
$errors = [];
$hasErrors = false;

if ($isPosted) {
    // Récupération de la saisie
    $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
    $author = filter_input(INPUT_POST, "author", FILTER_SANITIZE_STRING);
    $publisher = filter_input(INPUT_POST, "publisher", FILTER_SANITIZE_STRING);

    // Validation de la saisie
    // avec remplissage d'un tableau des erreurs
    if (empty($title)) {
        // Equivalent de aray_push
        $errors[] = "Vous devez saisir le titre";
    }
    if (empty($author)) {
        $errors[] = "Vous devez saisir l'auteur";
    }
    if (empty($publisher)) {
        $errors[] = "Vous devez saisir l'éditeur";
    }

    $hasErrors = count($errors) > 0;

    // si le nombre d'erreur est zéro on continue
    if (!$hasErrors) {
        // Création d'un tableau à partir de la saisie
        $newBook = [
            "id" => uniqid(),
            "title" => $title,
            "author" => $author,
            "publisher" => $publisher,
        ];

        // Ajout du nouveau à $bookList
        array_push($bookList, $newBook);

        // Conversion du tableau $bookList en json
        $bookListSerialized = json_encode($bookList);

        // Ecriture du contenu dans le fichier json
        file_put_contents($fileName, $bookListSerialized);

        //Redirection vers la page index.php
        header("location:index.php");
        exit;
    }

}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque</title>
    <link   rel="stylesheet"
            href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script>
        // Exécution du code uniquement quand le DOM est chargé
        $(document).ready(function(){
            // ciblage du conteneur du formulaire
            const formDiv = $("#formDiv");
            // ciblage du bouton d'affichage du formulaire
            const formDivButton = $("#showFormButton");

            <?php if($hasErrors): ?>
                formDivButton.text("Masquer le formulaire");
            <?php else: ?>    
                // masquage du formulaire
                formDiv.hide();
            <?php endif ?>

            // action sur le clic sur le bouton showFormButton
            formDivButton.on("click", function(){
                // Affichage ou masquage du formulaire
                formDiv.toggle(500);
                // Changement du texte du bouton
                if(formDivButton.text() == "Masquer le formulaire" ){
                    formDivButton.text("Afficher le formulaire");
                } else {
                    formDivButton.text("Masquer le formulaire");
                }
            });

            // Fermeture du message d'erreur
            $("#closeErrorButton").on("click", function(){
                $("#errorMessage").hide(400);
            });

            // Confirmation avant suppression
            $(".delete").on("click", function(){
                return confirm("voulez-vous vraiment supprimer ce livre ?");
            });
        });
    </script>

</head>
<body class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 bg-danger">
            <h1>Liste des livres</h1>

            <!-- Affichage des erreurs éventuelles -->
            <?php if ($hasErrors): ?>
                <div class="alert alert-danger" id="errorMessage"> 
                    <!-- Bouton pour fermer le message d'erreur -->
                    <button type="button" class="btn-close" id="closeErrorButton"></button>

                    <h3>Il y a des erreurs</h3>
                    <!-- boucle pour afficher les erreurs -->
                    <ul>
                        <?php foreach ($errors as $message): ?>
                            <li> <?=$message?> </li>
                        <?php endforeach?>
                    </ul>

                </div>
            <?php endif?>

            <!-- bouton pour afficher le formulaire -->
            <div class="mt-3 mb-2">
                <button type="button" id="showFormButton"
                        class="btn btn-primary">
                    Afficher le formulaire
                </button>
            </div>

            <!-- formulaire de création d'un livre -->
            <div class="m-3" id="formDiv">
                <h2>Nouveau livre</h2>
                <form method="post">
                    <div class="mb-2">
                        <label class="form-label">Titre</label>
                        <input type="text" name="title" class="form-control"
                        value="<?=$hasErrors ? $title : ""?>" >
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Auteur</label>
                        <input type="text" name="author" class="form-control"
                        value="<?=$hasErrors ? $author : ""?>" >
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Editeur</label>
                        <input type="text" name="publisher" class="form-control"
                        value="<?=$hasErrors ? $publisher : ""?>" >
                    </div>
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg w-50">
                        Valider
                        </button>
                    </div>

                </form>
            </div>
            <!-- fin du formulaire -->

            <!-- Tableau affichant la liste des livres -->
            <table class="table">
               <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Editeur</th>
                    <th></th>
               </tr>
               <!--
                    Boucle sur $bookList
                    pour afficher les données des livres
                -->
                <?php foreach ($bookList as $book): ?>
                    <tr>
                            <td><?=$book["title"]?></td>
                            <td><?=$book["author"]?></td>
                            <td><?=$book["publisher"]?></td>
                            <td>
                                <a href="delete.php?id=<?= $book["id"] ?>" class="btn btn-warning delete">supprimer</a>
                            </td>
                    </tr>
               <?php endforeach?>

            </table>
        </div>
    </div>

</body>
</html>