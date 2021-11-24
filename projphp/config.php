<?php
/*  Identifiants de la base de données. */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'demo');
 
/* connexion à la base de données MySQL  */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// verifier la connexion
if($link === false){
    die("erreur connexion. " . mysqli_connect_error());
}
?>