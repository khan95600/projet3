<?php
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
}
?>
<header>
	<img src="imagesp3/0.png"  alt="logo" class="logo" />
	<div class="headuser">

        <?php 

        if(isset($_SESSION['nom']))
        {	

      	 echo "bonjour ".$_SESSION['nom'] . " " . $_SESSION['prenom'];?>
   	<br/>
   	</div>
   	<div class="headerbtn">		  
   			  <input type="button" onclick=window.location.href="deconnexion.php" name="deconnexion" value="vous deconnecter">
       <?php
        } 
       
        ?>
    <br/>    
	<input type="button" onclick=window.location.href="profil.php" name="parametre" value="parametre de compte"><br/>
	<input type="button" onclick=window.location.href="modif.php" name="mdp" value="mot de passe oublié">
	</div>
</header>
