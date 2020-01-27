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
 
  if(isset($_SESSION['id_user']) AND $userinfo['id_user'] == $_SESSION['id_user']) 
  {
    if(isset($_POST['modification']))
     {
   
      $nom = htmlspecialchars($_POST['nom']);
      $prenom = htmlspecialchars($_POST['prenom']);
      $username = htmlspecialchars($_POST['username']);
      $password = sha1($_POST['password']);
      $question = htmlspecialchars($_POST['question']);
      $reponse = htmlspecialchars($_POST['reponse']);
   
      $pseudolenght = strlen($username);
        if($pseudolenght <= 255)
        {
          $requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE username = ?");
          $requser -> execute(array($username));
          $userexist = $requser->rowcount();
          if($userexist == 0)
          {

            $req = $bdd->prepare('UPDATE utilisateurs VALUES (NULL, :nom, :prenom, :username, :password, :question, :reponse)');
            $req->bindParam(':nom', $nom);
            $req->bindParam(':prenom', $prenom);
            $req->bindParam(':username', $username); 
            $req->bindParam(':password', $password);
            $req->bindParam(':question', $question);
            $req->bindParam(':reponse', $reponse);
               
            $req->execute();
            $message = "Les informations de votre compte ont bien été modifiés ! <a href=\"connexion.php\">Me connecter</a>";
            header('location: connexion.php');
            var_dump($req);
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
  }
}      
      ?>  

<!DOCTYPE html>
<html>
   <head>
      <link rel="stylesheet" type="text/css" href="style.css">
      <title>Page de profil</title>
      <meta charset="utf-8">
   </head>
   <body>
       <?php include ("headerprojet3.php"); ?> 
      <div align="center">
         <h2>Profil de <?php echo $_SESSION['nom'] . " " . $_SESSION['prenom']; ?></h2>
         <br /><br />
         Votre pseudo : <b><?php echo $_SESSION['username']; ?></b>
         <br /><br />
         Votre mot de passe : <?php echo $_SESSION['password']; ?>
         <br /><br />
        
 <form name="modification" action="" method="post">
<div class="champs">
      <p>votre nom</p>
    <input type="text" id="nom" name="nom" value="<?php if(isset($nom)) { echo $nom; } ?>"><br />
    <p>votre prenom</p>
    <input type="text" id="prenom" name="prenom" value="<?php if(isset($prenom)) { echo $prenom; } ?>"><br />

    <p>votre pseudo</p>
    <input  type="text" id="username" name="username" value="<?php if(isset($username)) { echo $username; } ?>"><br />

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
    <input  type="text" id="reponse" name="reponse" value="<?php if(isset($reponse)) { echo $reponse; } ?>"><br /><br />

      <input type="submit" name="modification" value="modifier le profil">
</div>

  </form>
  <a href="accueil.php">retour à l'accueil</a>
  <br />
  <?php
  if(isset($message))
  {
   echo '<font color="red">'.$message."</font>";
  }
  ?>
      </div>
   </body>
</html>