<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 18/07/17
 * Time: 16:22
 */
$path = "../";
include '../model/connection.php';

if(isset($_POST['btn-update']))
{
    $id = $_GET['edit_id'];
    $titre = $_POST['titre'];
    $article = $_POST['article'];
    $imgFile = $_FILES['picture']['name'];
    $tmp_dir = $_FILES['picture']['tmp_name'];
    $imgSize = $_FILES['picture']['size'];

    $stmt = $DB_con->prepare('SELECT titre, article, picture FROM article WHERE id =:id');
    $stmt->execute(array(':id'=>$id));
    $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($editrow);

    if($imgFile)
    {
        $upload_dir = '../images/'; // Repertoire de destination
        $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // recupere l'extension de l'image
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valide l'extension
        $picture = rand(1000,1000000).".".$imgExt;
        if(in_array($imgExt, $valid_extensions))
        {
            if($imgSize < 5000000)
            {
                unlink($upload_dir.$editrow['picture']);
                move_uploaded_file($tmp_dir,$upload_dir.$picture);
            }
            else
            {
                $errMSG = "Désolé, l'image est trop grande";
            }
        }
        else
        {
            $errMSG = "Désolé, seulement les formats JPG, JPEG, PNG & GIF files sont supportés.";
        }
    }
    else
    {
        // Si aucune image sélection on récupère la plus vieille
        $picture = $editrow['picture']; // Plus vieille image de la base de données
    }
    if($crud->modifier($id,$titre,$article,$picture))
    {
        $msg = "<div class='alert alert-info'>
    <strong>WOW!</strong> Udpdate effectué avec succès <a href='../index.php'>Accueil</a>!
    </div>";
    }
    else
    {
        $msg = "<div class='alert alert-warning'>
    <strong>Désolé !</strong> FATAL ERROR !
    </div>";
    }
}

if(isset($_GET['edit_id']))
{
    $id = $_GET['edit_id'];
    extract($crud->getID($id));
}

?>
<?php include '../vue/header.php'; ?>

<div class="container">
    <?php
    if(isset($msg))
    {
        echo $msg;
    }
    ?>
</div>

<br />

<div class="container">

    <form method='post' enctype="multipart/form-data">
        <table class='table table-bordered'>
            <tr>
                <td>Titre</td>
                <td><input type='text' name='titre' class='form-control' value="<?php echo $titre; ?>" required></td>
            </tr>
            <tr>
                <td>Article</td>
                <td><input type='text' name='article' class='form-control' value="<?php echo $article; ?>" required></td>
            </tr>
            <tr>
                <td>Votre image</td>
                <td><input type='file' name='picture' class='form-control' value="" required></td>
               <td><img src="../images/<?= $picture ?>" class="img-rounded" width="80px" height="80px" /></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" class="btn btn-primary" name="btn-update">
                        <span class="glyphicon glyphicon-edit"></span>  Mettre à jour
                    </button>
                    <a href="../index.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Annulé</a>
                </td>
            </tr>

        </table>
    </form>


</div>

<?php include_once '../vue/footer.php'; ?>
