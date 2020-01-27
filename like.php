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
 
 if(isset($_GET['t'], $_GET['id']) AND !empty($_GET['t']) AND !empty($_GET['id'])) {
 	$getid = (int) $_GET['id'];
 	$gett = (int) $_GET['t'];

$reqid = $bdd->prepare('SELECT * FROM acteurs WHERE id_acteur = ?');
$reqid->execute(array($getid));

$acteurinfo = $reqid->fetch();
 
$reqt = $bdd->prepare('SELECT * FROM utilisateurs WHERE id_user = ?');
$reqt->execute(array($gett));

$userinfo = $reqt->fetch();



$check = $bdd->query("SELECT * FROM likes WHERE id_acteur = '".$acteurinfo['id_acteur']."' AND id_user = '".$userinfo['id_user']."'");
$resultat = $check->fetch();

if ($resultat) {
	$checkbis = $bdd->query("SELECT * FROM likes WHERE id_acteur = '".$acteurinfo['id_acteur']."' AND id_user = '".$userinfo['id_user']."'");
	$result = $checkbis->fetch();

	if ($result['likes'] == 1) {
		$reqbs = $bdd->query('UPDATE likes SET likes = likes - 1 WHERE id_acteur = '.$acteurinfo['id_acteur'].' AND id_user = '.$userinfo['id_user'].'');
	} else {
		$reqtb = $bdd->query('UPDATE likes SET likes  = likes + 1 WHERE id_acteur = '.$acteurinfo['id_acteur'].' AND id_user = '.$userinfo['id_user'].'');

	}

	} else {
		$reqins = $bdd->query('INSERT INTO likes(id_acteur, id_user) VALUES ("'.$acteurinfo['id_acteur'].'", "'.$userinfo['id_user'].'")');
		$requp = $bdd->query('UPDATE likes SET likes = likes + 1 WHERE id_acteur = '.$acteurinfo['id_acteur'].' AND id_user = '.$userinfo['id_user'].'');
	}
}
	header('Location: http://projet3/page1.php?id_acteur='.$getid);
  ?>