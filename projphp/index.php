<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Forestia</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Produits Agroalimentaire</h2>
                        <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Ajouter un produit</a>
                    </div>
                    <?php
                    //  Inclur le fichier de configuration
                    require_once "config.php";
                    
                    //  Tentative d'exécution de la requête de sélection

                    $sql = "SELECT * FROM produits";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Nom Produit</th>";
                                        echo "<th>Origine</th>";
                                        echo "<th>Reference</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id_produit'] . "</td>";
                                        echo "<td>" . $row['nom_produit'] . "</td>";
                                        echo "<td>" . $row['origine_produit'] . "</td>";
                                        echo "<td>" . $row['reference_produit'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="read.php?id_produit='. $row['id_produit'] .'" class="mr-3" title="Consulter le produit" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="update.php?id_produit='. $row['id_produit'] .'" class="mr-3" title="Modifier le produit " data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete.php?id_produit='. $row['id_produit'] .'" title="Supprimer le produit" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // liberer $result
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>Aucun enregistrement</em></div>';
                        }
                    } else{
                        echo "Veuillez réessayer plus tard.";
                    }
 
                    // fermer la connexion
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>