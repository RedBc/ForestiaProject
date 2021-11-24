<?php
// Traiter l'opération de suppression après confirmation

if (isset($_POST["id_produit"]) && !empty($_POST["id_produit"])) {

    // Include config file
    require_once "config.php";

    // Préparer  l'instruction de suppression
    $sql = "DELETE FROM produits WHERE id_produit = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {

        // Lier les variables à l'instruction préparée 
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Définir les paramètres
        $param_id = trim($_POST["id_produit"]);

        //Tentative d'exécution de l'instruction préparée
        if (mysqli_stmt_execute($stmt)) {
            // Enregistrements supprimés avec succès. Rediriger vers la page index
            header("location: index.php");
            exit();
        }
        else {
            echo "Oops! un probléme. Veuillez réssayer à nouveau";
        }
    }

    // fermer le statement
    mysqli_stmt_close($stmt);

    // fermer la connexion
    mysqli_close($link);
}
else {
    // Vérifier l'existence du paramètre id
    if (empty(trim($_GET["id_produit"]))) {
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un produit </title>
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
                    <h2 class="mt-5 mb-3">Supprimer le produit</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id_produit" value="<?php echo trim($_GET["id_produit"]); ?>"/>
                            <p>Vous voulez vraiment supprimer ce produit ?</p>
                            <p>
                                <input type="submit" value="Oui" class="btn btn-danger">
                                <a href="index.php" class="btn btn-secondary">Non</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>