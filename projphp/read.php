<?php
//  Vérifier l'existence du paramètre id avant de poursuivre le traitement
if (isset($_GET["id_produit"]) && !empty(trim($_GET["id_produit"]))) {
    // Include le fichier config de la base de données 
    require_once "config.php";

    // Preparer la requette select
    $sql = "SELECT * FROM produits WHERE id_produit = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // // Lier les variables à l'instruction préparée 
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Seter parameters
        $param_id = trim($_GET["id_produit"]);

        // Tentative d'exécution de l'instruction préparée
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {

                // Récupère la ligne de résultat sous forme de tableau associatif
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Récupérer la valeur du champ individuel
                $nom_produit = $row["nom_produit"];
                $origine_produit = $row["origine_produit"];
                $reference_produit = $row["reference_produit"];
            }
            else {
                // URL ne contient pas de paramètre d'identifiant valide. Rediriger vers la page d'erreur
                header("location: error.php");
                exit();
            }

        }
        else {
            echo "Oops! erreur. Veuillez réesayer à nouveau";
        }
    }

    // fermeture du statement 
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
}
else {
    // L'URL ne contient pas de paramètre id. Rediriger vers la page d'erreur
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Détails Produits</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">Consulter Produit</h1>
                    <div class="form-group">
                        <label>Nom produit</label>
                        <p><b><?php echo $row["nom_produit"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Origine produit</label>
                        <p><b><?php echo $row["origine_produit"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Reference Produit</label>
                        <p><b><?php echo $row["reference_produit"]; ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Retour</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>