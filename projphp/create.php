<?php
// Includ le fichier de configuration
require_once "config.php";
 
// Définir les variables et les initialiser avec des valeurs vides
$nom_produit = $origine_produit = $reference_produit = "";
$nom_err = $origine_err = $reference_err = "";
 
//// Traitement des données  lors de la soumission du formulaire
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // nom valide
    $input_name = trim($_POST["nom_produit"]);
    if(empty($input_name)){
        $nom_err = "entrez le nom de produit";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nom_err = "entrez un nom valide de produit";
    } else{
        $nom_produit = $input_name;
    }

    //  origine valide
    $input_origine = trim($_POST["origine_produit"]);
    if(empty($input_origine)){
        $origine_err = "entrez l'origine de produit";     
    } else{
        $origine_produit = $input_origine;
    }
    //  reference valide
    $input_reference = trim($_POST["reference_produit"]);
    if(empty($input_reference)){
        $reference_err = "entrez la reference de produit ";     
    } elseif(!ctype_digit($input_reference)){
        $reference_err = "entre une valeur entier valide.";
    } else{
        $reference_produit = $input_reference;
    }

    //  Vérifier les erreurs de saisie avant l'insertion dans la base de données
    if(empty($nom_err) && empty($origine_err) && empty($reference_err)){
        // Préparer l'insertion
        $sql = "INSERT INTO produits (nom_produit, origine_produit, reference_produit) VALUES (?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            // Lier les variables à l'instruction préparée en tant que paramètres
            mysqli_stmt_bind_param($stmt, "sss", $param_nom, $param_origine, $param_reference);
            // Définir les paramètres
            $param_nom = $nom_produit;
            $param_origine = $origine_produit;
            $param_reference = $reference_produit;
            
            //  Tentative d'exécution de l'instruction préparée
            if(mysqli_stmt_execute($stmt)){
                // Enregistrements créés avec succès. Rediriger vers la page d'index
                header("location: index.php");
                exit();
            } else{
                echo "Oops! un probleme. Veuillez ressayer a nouveau";
            }
        }
         
        // fermer le statement
        mysqli_stmt_close($stmt);
    }
    
    // Fermer la connexion
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ajout d'un produit</title>
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
                    <h2 class="mt-5">Ajouter un nouveau produit</h2>
                    <p>Veuillez saisir les informations du produit.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nom Produit</label>
                            <input type="text" name="nom_produit" class="form-control <?php echo (!empty($nom_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nom_produit; ?>">
                            <span class="invalid-feedback"><?php echo $nom_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Origine Produit</label>
                            <textarea name="origine_produit" class="form-control <?php echo (!empty($origine_err)) ? 'is-invalid' : ''; ?>"><?php echo $origine_produit; ?></textarea>
                            <span class="invalid-feedback"><?php echo $origine_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Reference Produit</label>
                            <input type="text" name="reference_produit" class="form-control <?php echo (!empty($reference_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $reference_produit; ?>">
                            <span class="invalid-feedback"><?php echo $reference_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Valider">
                        <a href="index.php" class="btn btn-secondary ml-2">Annuler</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>