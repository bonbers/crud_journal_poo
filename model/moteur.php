<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 18/07/17
 * Time: 14:02
 */

class Crud
{
    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function creation($titre, $article, $picture)
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO article(titre,article,picture) VALUES(:utitre, :uarticle, :upicture)');
            $stmt->bindParam(':utitre', $titre);
            $stmt->bindParam(':uarticle', $article);
            $stmt->bindParam(':upicture', $picture);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getID($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM article WHERE id=:id");
        $stmt->execute(array(":id" => $id));
        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
        return $editrow;
    }

    public function modifier($id, $titre, $article, $picture)
    {
        try {
            $stmt = $this->db->prepare('UPDATE article SET titre=:utitre, article=:uarticle, picture=:upicture WHERE id=:id');
            $stmt->bindParam(':utitre', $titre);
            $stmt->bindParam(':uarticle', $article);
            $stmt->bindParam(':upicture', $picture);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt_edit =$this->db->prepare('SELECT titre, article, picture FROM article WHERE id =:id');
            $stmt_edit->execute(array(':id'=>$id));
            $editrow = $stmt_edit->fetch(PDO::FETCH_ASSOC);
            extract($editrow);

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function supprimer($id)
    {
        $stmt_select = $this->db->prepare('SELECT picture FROM article WHERE id =:uid');
        $stmt_select->execute(array(':uid'=>$id));
        $imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
        unlink("../images/".$imgRow['picture']);

        $stmt= $this->db->prepare('DELETE FROM article WHERE id =:id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return true;


    }
    public function dataview($query)
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        if($stmt->rowCount()>0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                ?>
                <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['titre']; ?></td>
                <td><?php echo $row['article']; ?></td>
                <td><img src="./images/<?php echo $row['picture']; ?>" class="img-rounded" width="80px" height="80px" />
                    </td>
                <td align="center">
                    <a href="./controller/modifier.php?edit_id=<?php echo $row['id']; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                </td>
                <td align="center">
                    <a href="./controller/supprimer.php?delete_id=<?php echo $row['id']; ?>"><i class="glyphicon glyphicon-remove-circle"></i></a>
                </td>
                </tr>

                <?php
            }
        }
        else
        {
            ?>
            <tr>
                <td>Rien ici...</td>
            </tr>
            <?php
        }

    }

    public function paging($query,$records_per_page)
    {
        $starting_position=0;
        if(isset($_GET["page_no"]))
        {
            $starting_position=($_GET["page_no"]-1)*$records_per_page;
        }
        $query2=$query." limit $starting_position,$records_per_page";
        return $query2;
    }

    public function paginglink($query,$records_per_page)
    {

        $self = $_SERVER['PHP_SELF'];

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $total_no_of_records = $stmt->rowCount();

        if($total_no_of_records > 0)
        {
            ?><ul class="pagination"><?php
            $total_no_of_pages=ceil($total_no_of_records/$records_per_page);
            $current_page=1;
            if(isset($_GET["page_no"]))
            {
                $current_page=$_GET["page_no"];
            }
            if($current_page!=1)
            {
                $previous =$current_page-1;
                echo "<li><a href='".$self."?page_no=1'>First</a></li>";
                echo "<li><a href='".$self."?page_no=".$previous."'>Previous</a></li>";
            }
            for($i=1;$i<=$total_no_of_pages;$i++)
            {
                if($i==$current_page)
                {
                    echo "<li><a href='".$self."?page_no=".$i."' style='color:red;'>".$i."</a></li>";
                }
                else
                {
                    echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
                }
            }
            if($current_page!=$total_no_of_pages)
            {
                $next=$current_page+1;
                echo "<li><a href='".$self."?page_no=".$next."'>Next</a></li>";
                echo "<li><a href='".$self."?page_no=".$total_no_of_pages."'>Last</a></li>";
            }
            ?></ul><?php
        }
    }


}