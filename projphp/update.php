<?php
// Include config file
require_once "config.php";


// Définir les variables et initialiser avec des valeurs vides
$nom_produit = $origine_produit = $reference_produit = "";
$nom_err = $origine_err = $reference_err = "";

// Traitement des données du formulaire lors de la soumission du formulaire
if (isset($_POST["id_produit"]) && !empty($_POST["id_produit"])) {
    // Obtenir la valeur d'entrée 
    $id_produit = $_POST["id_produit"];

    // Nom du produit valide
    $input_name = trim($_POST["nom_produit"]);
    if (empty($input_name)) {
        $nom_err = " entrer le nom du produit.";
    }
    elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $nom_err = "entrer un nom valide du produit.";
    }
    else {
        $nom_produit = $input_name;
    }

    // Origine du produit valide
    $input_origine = trim($_POST["origine_produit"]);
    if (empty($input_origine)) {
        $origine_err = "entrer l'origine du produit";
    }
    else {
        $origine_produit = $input_origine;
    }

    //Origine du reference valide
    $input_reference = trim($_POST["reference_produit"]);
    if (empty($input_reference)) {
        $reference_err = "entrer la reference du produit";
    }
    elseif (!ctype_digit($input_reference)) {
        $reference_err = "entrer une reference entier valide du produit ";
    }
    else {
        $reference_produit = $input_reference;
    }

    // Vérifier les erreurs de saisie avant l'insertion dans la base de données
    if (empty($nom_err) && empty($origine_err) && empty($reference_err)) {
        // Preparer la requette de modification 
        $sql = "UPDATE produits SET nom_produit=?, origine_produit=?, reference_produit=? WHERE id_produit=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Lier des variables à l'instruction préparée en tant que paramètres
            mysqli_stmt_bind_param($stmt, "sssi", $param_nom, $param_origine, $param_reference, $param_id);

            // Seter les parameteres
            $param_nom = $nom_produit;
            $param_origine = $origine_produit;
            $param_reference = $reference_produit;
            $param_id = $id_produit;

            // Tenter d'exécuter l'instruction de la requette préparée
            if (mysqli_stmt_execute($stmt)) {
                // Enregistrements mis à jour avec succès. Rediriger vers la page de l'index
                header("location: index.php");
                exit();
            }
            else {
                echo "Oops! un probléme. Veuillez réssayer à nouveau";
            }
        }

        // fermer le statement
        mysqli_stmt_close($stmt);
    }

    // fermer la connexion
    mysqli_close($link);
}
else {
    // Vérifier l'existence du paramètre id avant de poursuivre le traitement
    if (isset($_GET["id_produit"]) && !empty(trim($_GET["id_produit"]))) {
        // Obtenir le paramètre d'URL
        $id_produit = trim($_GET["id_produit"]);

        // Preparer la requette select
        $sql = "SELECT * FROM produits WHERE id_produit = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            //Lier des variables à la requette préparée 
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Seter les parameteres
            $param_id = $id_produit;

            // Tenter d'exécuter l'instruction préparée

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    //Récupérer la ligne de résultat sous forme de tableau associatif
                  
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $nom_produit = $row["nom_produit"];
                    $origine_produit = $row["origine_produit"];
                    $reference_produit = $row["reference_produit"];
                }
                else {
                    //L'URL ne contient pas d'identifiant valide. Rediriger vers la page d'index
                    header("location: error.php");
                    exit();
                }

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
        // L'URL ne contient pas de paramètre id. Rediriger vers la page d'index
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modifier Produit</title>
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
                    <h2 class="mt-5">Modifier Produit</h2>
                    <p>Veuillez modifier les valeurs d'entrée et soumettre pour mettre à jour l'enregistrement du produit</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom_produit" class="form-control <?php echo(!empty($nom_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nom_produit; ?>">
                            <span class="invalid-feedback"><?php echo $nom_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Origine</label>
                            <textarea name="origine_produit" class="form-control <?php echo(!empty($origine_err)) ? 'is-invalid' : ''; ?>"><?php echo $origine_produit; ?></textarea>
                            <span class="invalid-feedback"><?php echo $origine_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Reference</label>
                            <input type="text" name="reference_produit" class="form-control <?php echo(!empty($reference_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $reference_produit; ?>">
                            <span class="invalid-feedback"><?php echo $reference_err; ?></span>
                        </div>
                        <input type="hidden" name="id_produit" value="<?php echo $id_produit; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Valider">
                        <a href="index.php" class="btn btn-secondary ml-2">Annuler</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>