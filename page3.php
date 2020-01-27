<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
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

if(isset($_GET['id_acteur']) AND !empty($_GET['id_acteur'])) {

	$getid = htmlspecialchars($_GET['id_acteur']);
	$acteur = $bdd->prepare('SELECT * FROM acteur WHERE id_acteur = ?');
	$acteur->execute(array($getid));
	$acteur = $acteur->fetch();
	$id = $acteur['id_acteur'];
	$likes = $bdd->prepare('SELECT id FROM likes WHERE id_acteur = ?');
	$likes->execute(array($id));
	$likes = $likes->rowcount();

	$dislike = $bdd->prepare('SELECT id FROM dislikes WHERE id_acteur = ?');
	$dislike->execute(array($id));
	$dislike = $dislike->rowcount();
	

	if(isset($_POST['submit_commentaire'])) { 
	
		if(isset($_POST['username'],$_POST['commentaire']) AND !empty($_POST['username']) AND !empty($_POST['commentaire'])) {

			$username = htmlspecialchars($_POST['username']);
			$commentaire = htmlspecialchars($_POST['commentaire']);
			if ($username AND $commentaire <= 1){

			$com = $bdd->prepare('INSERT INTO commentaire (id_article, username, commentaire, date_commentaire) VALUES (?,?,?, NOW())');
			$com->execute(array($getid,$username,$commentaire));
			$c_msg = "votre commentaire a été ajouté";
			
			} else {
				$c_msg = "vous avez déjà déposé un commentaire";
			}
		

		} else {
			$c_msg = " Erreur: tout les champs doivent etre complétés";
		}
	}
	$commentaires = $bdd->prepare('SELECT * FROM commentaire WHERE id_article = ? ORDER BY id DESC');
	$commentaires->execute(array($getid));
?>
<?php include("headerprojet3.php");?>
<div class="page">
	<div class="description">
<h1><img src="imagesp3/Dsa_france.png" class="logo3">
</h1>
<h2><?= $acteur['contenu']; ?>

</h2>
<br />
<?php
}
?>
</div>
<div class="commentaires">
<h2>Commentaires:</h2>
<a href="like.php?t=1&id=<?= $id ?>">J'aime</a> (<?= $likes ?>)
<br />
<a href="like.php?t=2&id=<?= $id ?>">J'aime pas</a> (<?= $dislike ?>)
<form method="POST">
	<input type="text" name="username" placeholder="votre pseudo" /><br />
	<textarea name="commentaire" placeholder="votre commentaire..."></textarea><br />
	<input type="submit" value="Poster mon commentaire" name="submit_commentaire" />
</form>
<?php
if(isset($c_msg)) { echo $c_msg; } ?>
<br />
<?php 
while($c = $commentaires->fetch()) { ?>
	<div id="tableau"><ul>
		<li><?= $c['username'] ?></li> 
		<li><?= $c['date_commentaire'] ?></li> 
		<li><?= $c['commentaire'] ?></li></div>
<?php } ?>
</div>
</div>
</body>
</html>

