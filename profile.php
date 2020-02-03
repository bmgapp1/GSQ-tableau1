<?php
 
 $HeureDeSubdivisionJournaliere40=00;
 $HeureDeSubdivisionJournaliere41=06;
 $HeureDeSubdivisionJournaliere42=13;
 $HeureDeSubdivisionJournaliere43=20;
 $HeureDeSubdivisionJournaliere44=24;

 try
{
// On se connecte à MySQL
//$bdd = new PDO('mysql:host=localhost;dbname=ssm2;charset=utf8', 'BAMOGO', 'bamogo');
$bdd = new PDO('mysql:host=remotemysql.com;dbname=QeU06kz7zq;charset=utf8', 'QeU06kz7zq', 'TDIrQnWETs');
}
catch(Exception $e)
{
// En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}

// Si tout va bien, on peut continuer

// On récupère tout le contenu de la table jeux_video
$reponse = $bdd->query('SELECT a.matricule, nom,prenom,   jour,JourHTE,JourHJF,JourHNF,JourHNN FROM agent a , lectjour l where a.matricule=l.matricule order by nom,jour ');
$reponse2Agent = $bdd->query('SELECT matricule, nom,prenom, DateNaiss,DatePriseSvc,Codeposition,Fonction,id_h FROM agent order by nom ');

// On affiche chaque entrée une à une
?>

<?php
  class Agent {
      public $mat;
      public $nom;
      public $prenom;
      public $datenaiss;
      public $datePriseSVC;
      public $Codeposition;
      public $Fonction;
      public $id_h;
  }
  $agentp = array();
  $i=0;
 while ($donnees = $reponse2Agent->fetch())
   { 
    $agentp[$i] = new agent;
   $agentp[$i] ->mat = $donnees[0];
   $agentp[$i] ->nom = $donnees[1];
   $agentp[$i] ->prenom = $donnees[2];
   $agentp[$i] ->datenaiss = $donnees[3];
   $agentp[$i] ->datePriseSVC = $donnees[4];
   $agentp[$i] ->Codeposition = $donnees[5];
   $agentp[$i] ->Fonction = $donnees[6];
   $agentp[$i] ->id_h = $donnees[7];
    $i = $i+1;
	} 
  $reponse2Agent->closeCursor(); // Termine le traitement de la requête
  ?>

<?php
// Nous devons utiliser des sessions, vous devez donc toujours démarrer les sessions en utilisant le code ci-dessous.
session_start();
// Si l'utilisateur n'est pas connecté, redirigez vers la page de connexion ...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit();
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'BAMOGO';
$DATABASE_PASS = 'bamogo';
$DATABASE_NAME = 'ssm2';
/*$DATABASE_HOST = 'remotemysql.com';
$DATABASE_USER = 'QeU06kz7zq';
$DATABASE_PASS = 'TDIrQnWETs';
$DATABASE_NAME = 'QeU06kz7zq';*/
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Nous n'avons pas le mot de passe ou les informations de courrier électronique stockées dans les sessions, nous pouvons donc obtenir les résultats de la base de données.
$stmt = $con->prepare('SELECT password, email,nom,prenom,id_h FROM comptes c, agent a WHERE a.matricule=c.matricule and c.matricule = ?');
// Dans ce cas, nous pouvons utiliser l'ID de compte pour obtenir les informations de compte.
$stmt->bind_param('i', $_SESSION['matricule']);
$stmt->execute();
$stmt->bind_result($password, $email,$nom,$prenom,$id_h);
$stmt->fetch();
$stmt->close();
?>

<?php
try
{
// On se connecte à MySQL
// RECUPERATION DE LA REQUETE PROGRAMME DE LA SEMAINE
//$bdd = new PDO('mysql:host=10.26.12.87;dbname=ssm2;charset=utf8', 'BAMOGO', 'bamogo');
$bdd = new PDO('mysql:host=remotemysql.com;dbname=QeU06kz7zq;charset=utf8', 'QeU06kz7zq', 'TDIrQnWETs');
}
catch(Exception $e)
{
// En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}

// Si tout va bien, on peut continuer

// On récupère tout le contenu de la table jeux_video
$reponseProgThisWeek = $bdd->query('SELECT  concat(a.matricule," ",nom," ", prenom) as "Agent de quart",
		                        Fonction,
                                concat(" est de quart le ",p.jour," de ",HeureD," à ",HeureF) as "MAINTENANT.."
                        FROM ssm2.programmer p , agent a,decomptejour d
                        where a.matricule=p.matricule and p.CodeDecompteJour=d.CodeDecompteJour
                            and to_days(jour)>to_days(concat(year(current_date()),"-01-01"))-7 -- Pour eliminer les donnees des années préc .
				            and weekofyear(jour)=weekofyear(current_date())
                        order by id_h, Jour,hour(heured); ');

// On affiche chaque entrée une à une
?>

<?php
  class Progr {
      public $MatNomPrenom;
      public $Fonction;
      public $EDQuart;
  }
  $Progra = array();
  $i=0;
 while ($donnees = $reponseProgThisWeek->fetch())
   { 
    $Progra[$i] = new Progr;
   $Progra[$i] ->MatNomPrenom = $donnees[0];
   $Progra[$i] ->Fonction = $donnees[1];
   $Progra[$i] ->EDQuart = $donnees[2];
 
    $i = $i+1;
	} 
  $reponseProgThisWeek->closeCursor(); // Termine le traitement de la requête
  ?>





<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?=$_SESSION['name']?> Profile Page<?=$_SESSION['name']?></title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="icon" href="image/iconeG256.ico"/>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1><a href="home.php"><img src="image/iconeG256.ico" alt="GSQUART from bmgapp1@gmail.com" height="25" width="25">SQUART</a></h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Se déconnecter</a>
			</div>
		</nav>
		<div class="content">
			<h2>GSQUART Profile Page</h2>
			<div>
				<p>Your account details are below:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><?=$password?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=$email?></td>
					</tr>
					<tr>
						<td>NOM:</td>
						<td><?=$nom?></td>
					</tr><tr>
						<td>IDH:</td>
						<td><?=$id_h?></td>
					</tr>
				</table>
			</div>
		    <div>
		     <table>
		        <?php  for ($i=0;$i<count($Progra);$i++){ ?> 
		        <tr>
                     <td><?= $Progra[$i]->MatNomPrenom.' '.$Progra[$i]->EDQuart; ?> </td>
                </tr> <?php } ?>
      		 </table>
		   </div>
		   </div>
	</body>
</html>