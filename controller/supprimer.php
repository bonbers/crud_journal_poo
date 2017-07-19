<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 19/07/17
 * Time: 08:55
 */
$path = "../";
include '../model/connection.php';

if(isset($_POST['btn-del']))
{
    $id = $_GET['delete_id'];
    $crud->supprimer($id);
    header("Location: ./supprimer.php?deleted");
}
?>
<?php
include '../vue/header.php';
?>

<div class="container">

 <?php
 if(isset($_GET['deleted']))
 {
     ?>
     <div class="alert alert-success">
         <strong>Succès !</strong> Article supprimé !
     </div>
     <?php
 }
 else
 {
     ?>
     <div class="alert alert-danger">
         <strong>Sure </strong> de vouloir supprimer l'article ?
     </div>
     <?php
 }
 ?>
</div>
<?php
if(isset($_GET['delete_id']))
{
    ?>
    <div class="container">
    <table class='table table-bordered table-responsive'>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Article</th>
            <th>Image</th>
        </tr>
        <?php
        $stmt = $DB_con->prepare("SELECT * FROM article WHERE id=:id");
        $stmt->execute(array(":id"=>$_GET['delete_id']));
        while($row=$stmt->fetch(PDO::FETCH_BOTH))
        {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['titre']; ?></td>
                <td><?php echo $row['article']; ?></td>
                <td><img src="../images/<?php echo $row['picture']; ?>" class="img-rounded" width="80px" height="80px" /></td>
            </tr>
            <?php
        }
        ?>
    </table>
    </div>
    <?php
}
?>
    <div class="container">
        <p>
            <?php
            if(isset($_GET['delete_id']))
            {
            ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
            <button class="btn btn-large btn-primary" type="submit" name="btn-del"><i class="glyphicon glyphicon-trash"></i> &nbsp; OUI</button>
            <a href="../index.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; NON</a>
        </form>
        <?php
        }
        else
        {
            ?>
            <a href="../index.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Retour à l'accueil</a>
            <?php
        }
        ?>
    </div>
<?php
include '../vue/footer.php';
?>