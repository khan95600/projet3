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
if(isset($_POST['formconnexion']))
{   
    
    $userconnect = htmlspecialchars($_POST['userconnect']);
    $passconnect = sha1($_POST['passconnect']);
    if(!empty($userconnect) AND !empty($passconnect))
    {
    
        $requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE username = ? AND password = ?");
        $requser->execute(array($userconnect, $passconnect));
        $userexist = $requser->rowcount();
        if($userexist == 1)
        {
             $userinfo = $requser->fetch(); 
             $_SESSION['id_user'] = $userinfo['id_user'];
             $_SESSION['nom'] = $userinfo['nom'];
             $_SESSION['prenom'] = $userinfo['prenom'];
             $_SESSION['username'] = $userinfo['username'];
             $_SESSION['password'] = $userinfo['password'];
             header("Location: accueil.php?id_user=".$_SESSION['id_user']);
        
        }

        else
        {
            $message = "mauvais username ou mot de passe";
        }
    }
    else
    {
        $message = "tous les champs doivent être remplis";
    }
}   
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="style.css">
        <title>page de connexion</title>
    </head>
    <body>
        <?php include ("headerprojet3.php"); ?>  

    
        <p>Veuillez entrer votre identifiant et votre mot de passe</p>
        <form action="" method="post">
            <p>
           <div class="champs">
    <label for="username">Username:</label><br />
    <input required="required" type="text" id="username" name="userconnect">
</div>
<br />
<div>
    <label for="pass">Password (8 characters minimum):</label><br />
    <input required="required" type="password" id="pass" name="passconnect"
           minlength="8" required><br />
        <input type="submit" name="formconnexion" value="valider">
</div>

        </form><br />
        <?php
        if(isset($message)){
        echo $message;
        }
        ?>
        <input type="button" onclick=window.location.href="creation.php" name="creation" value="créer un profil">

    </body>
</html>