<?php
session_start();
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=gbaf;charset=utf8', 'root', '');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}        
if(isset($_GET['id_user']) AND $_GET['id_user'] > 0) 
{
   $getid = intval($_GET['id_user']);
   $requser = $bdd->prepare('SELECT * FROM utilisateurs WHERE id_user = ?');
   $requser->execute(array($getid));
   $userinfo = $requser->fetch();
 
  if(isset($_SESSION['id_user'])) { 
   $requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE id_user = ?");
   $requser->execute(array($_SESSION['id']));
   $user = $requser->fetch();
   if(isset($_POST['nusername']) AND !empty($_POST['nusername']) AND $_POST['nusername'] !=$user['nusername']); {
   	echo "coucou";
   $nusername = htmlspecialchars($_POST['nusername']);
   $reqnuser = $bdd->prepare("UPDATE utilisateurs SET username = ? WHERE ID = ? ");
   $reqnuser->execute(array($nusername, $_SESSION['id_user']));
   header('Location: profil.php?id='.$_SESSION['id']);
   } 
   if(isset($_POST['nureponse']) AND !empty($_POST['nureponse']) AND $_POST['nureponse'] !=$user['nureponse']); {
   $nureponse = htmlspecialchars($_POST['nureponse']);
   $reqnuser = $bdd->prepare("UPDATE utilisateurs SET reponse = ? WHERE ID = ? ");
   $reqnuser->execute(array($nureponse, $_SESSION['id_user']));
   header('Location: profil.php?id='.$_SESSION['id']); 
   }
   if(isset($_POST['nusername']) AND !empty($_POST['nusername']) AND isset($_POST['nureponse']) AND !empty($_POST['nureponse'])) {
   	$nupass = sha1($_POST['nupass']);
   	$insertnupass = $bdd->prepare("UPDATE utilisateurs SET password = ? WHERE id_user = ?");
   	$insertnupass->execute(array($nupass, $_SESSION['id_user']));
   	$message = "le mot de passe a bien été modifié";
   	header('Location: profil.php?id=' .$_SESSION['id_user']);
   } else {
   	$message = "La reponse à la question est incorrecte";
   }
  }
}
?> 
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>modification du mot de passe</title>
</head>
<body>
  <?php include ("headerprojet3.php"); ?> 

	<h2>Modification du mot de passe</h2>
	<form name="mdpform" method="POST" action="">
<p>
           <div class="champs">
    <label for="username">Username:</label><br />
    <input required="required" type="text" name="nusername" placeholder="votre username">
</div>
<br />
<div>
    <label for="reponse">Réponse à la question secrète:</label><br />
    <input required="required" type="text" name="nureponse"> <br />

    <label for="nmdp">nouveau mot de passe:</label><br />
    <input required="required" minlength="8" type="text" name="nupass">
        <input type="submit" name="mdpform" value="valider">
</div>
<a href="accueil.php">retour à l'accueil</a>
        </form><br />
        <?php
        if(isset($message)){
        echo $message;
        }
        ?>
       
	</form>
</body>
</html>
