<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <?php
        if (isset($_POST['ok'])) {
            require_once("class_files.php");
            require_once("connexion.php");
            // Vérifie si un fichier a été téléchargé
            if ($_FILES['myVar']['error'] === UPLOAD_ERR_OK) {
                $file_name = $_FILES['myVar']['name'];
                $file_tmp = $_FILES['myVar']['tmp_name'];
                $file_size = $_FILES['myVar']['size'];
                $file_type = $_FILES['myVar']['type'];

                // Crée une instance de la classe GestionnaireFichiers
                $gestionnaire = new GestionnaireFichiers('my doc', "mysql:host=$host;dbname=$dbname", $username, $password);
                // Enregistre le fichier dans le dossier "my doc" avec un nom unique
                $nom_unique = $gestionnaire->enregistrerFichier($file_name, file_get_contents($file_tmp), $file_type);
                if ($nom_unique === false) {
                    // Message d'erreur d extention
                    echo "Une Erreur est survenue!!! Desole,veuillez re-&eacute;ssayer.";
                } else {
                    // Affiche un message de succès avec le nom unique du fichier
                    echo "Le fichier $file_name a été téléchargé avec succès sous le nom $nom_unique.";
                }
            } else {
                // Affiche un message d'erreur
                echo "Une erreur est survenue lors du téléchargement du fichier.";
            }
        }
        ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="myVar" />
            <button name="ok">Send</button>
        </form>
    </div>
</body>

</html>