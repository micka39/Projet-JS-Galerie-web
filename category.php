<?php
$titre = "Gestion des categories";
include_once("header.php"); 
?>


<div class="jumbotron">
      <div class="container">
        <h1>Galerie web</h1>
        
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
          
                                    <!--                            Add Category                   -->
          
          <button type="button" class="btn btn-default navbar-btn" data-toggle="modal" data-target="#myModal">Ajouter une catégorie</button>
          
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">Ajouter une catégorie</h4>
                                    </div>
                                   
                                    <div class="modal-body">
                                        <form method="POST" action="category/ajout.php">
                                         <div class="input-group">
                                            <span class="input-group-addon">Nom</span>
                                            <input type="text" class="form-control" placeholder="Nom" name="name" >
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">Description</span>
                                            <input type="text" class="form-control" placeholder="Description" name="description">
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">Date de création</span>
                                            <input type="date" class="form-control" placeholder="Username" name="creationDate">
                                        </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                <button type="submit" class="btn btn-primary">Ajouter</button>
                                            </div>
                                        </form>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
              
                                                <!--        Table     -->
                        
          <table class="table">
              <tr>
                  <td><b>Id</b></td>
                  <td><b>Nom</b></td>
                  <td><b>Description</b></td>
                  <td><b>Date de création</b></td>
                  <td><b>Action</b></td>
              </tr> 
              <tr>
           <?php 
           
           require ("class/config.php");
           
          $connect=connectPdo();
           
          $sql= "select * from category";
          $response = $connect->query($sql);
          
         while($row = $response->fetch())
         {
             echo "<tr>";
             echo "<td>" . $row["idcategory"] . "</td>" ;
             echo "<td>" . $row["name"] . "</td>" ;
             echo "<td>" . $row["description"] . "</td>" ;
             echo "<td>" . $row["time"] . "</td>" ;
             echo "<td>";
             echo "<div class=\"btn-group\">";
             echo  "<button type=\"button\" class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\">";
             echo  "     Action <span class=\"caret\"></span>";
             echo  " </button>";
             echo  " <ul class=\"dropdown-menu\" role=\"menu\">";
             echo  " <li><a href=\"category/modifier.php?id=".$row["idcategory"]."&name=".$row["name"]."&description=".$row["description"]."&time=".$row["time"]."\">Modifier</a></li>";
             echo  " <li><a href=\"category/supprimer.php?id=".$row["idcategory"]."&name=".$row["name"]."\">Supprimer</a></li>";
             echo  " <li><a href=\"category/afficher.php?id=".$row["idcategory"]."&name=".$row["name"]."\">Afficher</a></li>";
             echo  " </ul>";
             echo  "</div>";
             echo  "</td>";
             echo "</tr>";
        }
          
           ?>

              </td>
              </tr>
          </table>
          
          </div>


      </div>
      <hr>

<?php include_once("footer.php"); ?>