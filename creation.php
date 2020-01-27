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
if(isset($_POST['inscription']))
{
	
	$nom = htmlspecialchars($_POST['nom']);
	$prenom = htmlspecialchars($_POST['prenom']);
	$username = htmlspecialchars($_POST['username']);
	$password = sha1($_POST['password']);
	$question = htmlspecialchars($_POST['question']);
	$reponse = htmlspecialchars($_POST['reponse']);

	if(!empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['username']) AND !empty($_POST['password']) AND !empty($_POST['question']) AND !empty($_POST['reponse']))
	{
		$pseudolenght = strlen($username);
		if($pseudolenght <= 255)
		{
			$requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE username = ?");
			$requser -> execute(array($username));
			$userexist = $requser->rowcount();
				if($userexist == 0)
				{

					$req = $bdd->prepare('INSERT INTO utilisateurs VALUES (NULL, :nom, :prenom, :username, :password, :question, :reponse)');
					$req->bindParam(':nom', $nom);
					$req->bindParam(':prenom', $prenom);
					$req->bindParam(':username', $username);
					$req->bindParam(':password', $password);
					$req->bindParam(':question', $question);
					$req->bindParam(':reponse', $reponse);
					

					$req->execute();
					$message = "Votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>";
				}
				else
				{
				$message = "username déjà pris";
				}
		}
		else
		{
		$message = " le pseudo ne doit pas depasser 255 caractères";
		}
}		
else
{
$message = "tout les champs doivent etre complétés";
}
}					
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>création de profil</title>
</head>
<body>
	 <?php include ("headerprojet3.php");?>  

 <form name="inscription" action="" method="post">
<div class="champs">
   	<p>votre nom</p>
    <input required="required" type="text" id="nom" name="nom" value="<?php if(isset($nom)) { echo $nom; } ?>"><br />
    <p>votre prenom</p>
    <input required="required" type="text" id="prenom" name="prenom" value="<?php if(isset($prenom)) { echo $prenom; } ?>"><br />

    <p>votre pseudo</p>
    <input required="required" type="text" id="username" name="username" value="<?php if(isset($username)) { echo $username; } ?>"><br />

    <p>votre mot de passe</p>
    <input type="password" id="pass" name="password"
           minlength="8" required value="<?php if(isset($password)) { echo $password; } ?>"><br />

     <p>question secrète</p>
     <label for="question">choisissez la question :</label>
     <select name="question" id="question" value="<?php if(isset($question)) { echo $question; } ?>">

     <option value="">--choisissez une option--</option>
     <option value="mere">le nom de jeune fille de votre mère</option>
     <option value="animal">le nom de votre animal de compagnie</option>
     <option value="voyage">la destination de votre dernier voyage</option>
     <option value="naissance">votre ville de naissance</option>
     </select>      
    <p>la reponse à la question secrète</p>
    <input required type="text" id="reponse" name="reponse" value="<?php if(isset($reponse)) { echo $reponse; } ?>"><br /><br />

      <input type="submit" name="inscription" value="creer le profil">
</div>

  </form>
  <br />
  <?php
  if(isset($message))
  {
  	echo '<font color="red">'.$message."</font>";
  }
  ?>
</body>
</html>