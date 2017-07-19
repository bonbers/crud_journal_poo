<?php
$path = "./";
include './model/connection.php';
?>
<?php
include './vue/header.php';
?>
    <div class="container">

        <div class="page-header">
            <h1 class="h2">Tous les articles<a class="btn btn-default" href="./controller/creation.php"> <span class="glyphicon glyphicon-plus"></span> &nbsp;Ajouter un nouveau</a></h1>
        </div>
    </div>
    <br />

    <div class="container">
        <table class='table table-bordered table-responsive'>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Article</th>
                <th>Image</th>
                <th colspan="2" align="center">Actions</th>
            </tr>
            <?php
            $query = "SELECT * FROM article";
            $records_per_page=3;
            $newquery = $crud->paging($query,$records_per_page);
            $crud->dataview($newquery);
            ?>
            <tr>
                <td colspan="7" align="center">
                    <div class="pagination-wrap">
                        <?php $crud->paginglink($query,$records_per_page); ?>
                    </div>
                </td>
            </tr>

        </table>
    </div>
<?php include './vue/footer.php';