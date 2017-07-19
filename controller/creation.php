<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 18/07/17
 * Time: 14:26
 */
$path = "../";
include '../model/connection.php';

if(isset($_POST['btnsave'])) {
    $titre = $_POST['titre'];// titre
    $article = $_POST['article'];// article
//    $picture = $_FILES['picture'];
    $imgFile = $_FILES['picture']['name'];
    $tmp_dir = $_FILES['picture']['tmp_name'];
    $imgSize = $_FILES['picture']['size'];

    if (empty($titre)) {
        $errMSG = "Entrez un titre valide";
    } else if (empty($article)) {
        $errMSG = "Entrez un article cohérent !";
    } else if (empty($imgFile)) {
        $errMSG = "Sélectionner une image";
    } else {
        $upload_dir = '../images/'; // Répertoire de destination

        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // extension image

        // validation extension
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // validation de l'extension

        // Renommer image
        $picture = rand(1000, 1000000) . "." . $imgExt;

        // Validation format image
        if (in_array($imgExt, $valid_extensions)) {
            // Taille fichier max '5MB'
            if ($imgSize < 5000000) {
                move_uploaded_file($tmp_dir, $upload_dir . $picture);
            } else {
                $errMSG = "Désolé image trop grande";
            }
        } else {
            $errMSG = "Désolé, seulement les formats JPG, JPEG, PNG & GIF sont supportés.";
        }

        if ($crud->creation($titre, $article, $picture)) {
            header("Location: ./creation.php?inserer");
        } else {
            header("Location: ./creation.php?echec");
        }
    }
}
?>
<?php
include '../vue/header.php';
?>
<?php
if(isset($_GET['inserer']))
{
    ?>
    <div class="container">
        <div class="alert alert-info">
            <strong>WOW!</strong> Ajout de l'article avec succès <a href="../index.php">Retour Accueil</a>!
        </div>
    </div>
    <?php
}
else if (isset($_GET['echec']))
{
    ?>
    <div class="container">
        <div class="alert alert-warning">
            <strong>Désolé !</strong> FATAL ERROR !!!!!!!!!
        </div>
    </div>
    <?php
}
?>

<div class="container">

    <form method='post' enctype="multipart/form-data">

        <table class='table table-bordered'>

            <tr>
                <td>Titre</td>
                <td><input type='text' name='titre' class='form-control' required></td>
            </tr>

            <tr>
                <td>Article</td>
                <td><input type='text' name='article' class='form-control' required></td>
            </tr>

            <tr>
                <td>Votre image</td>
                <td><input type='file' name='picture' class='form-control' required></td>
            </tr>

            <tr>
                <td colspan="2">
                    <button type="submit" class="btn btn-primary" name="btnsave">
                        <span class="glyphicon glyphicon-plus"></span> Ajouter l'article
                    </button>
                    <a href="../index.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Retour à l'accueil</a>
                </td>
            </tr>

        </table>
    </form>
</div>

<?php include '../vue/footer.php'; ?>