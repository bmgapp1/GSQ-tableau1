<?php
// Nous devons utiliser des sessions, vous devez donc toujours démarrer les sessions en utilisant le code ci-dessous.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit();
}
?>


<?php //POUR LE TABLEAU
 
 $HeureDeSubdivisionJournaliere40=00;
 $HeureDeSubdivisionJournaliere41=06;
 $HeureDeSubdivisionJournaliere42=13;
 $HeureDeSubdivisionJournaliere43=20;
 $HeureDeSubdivisionJournaliere44=24;

 try
{
// On se connecte à MySQL
$bdd = new PDO('mysql:host=localhost;dbname=ssm2;charset=utf8', 'BAMOGO', 'bamogo');
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


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="icon" href="image/iconeG256.ico"/>
		<script src="js/codetest.js" type ="text/javascript">
	    </script>
	</head>
	<body class="loggedin">
	  <nav class="navtop">
	    <div>
		  <h1><a href="home.php"><img src="image/iconeG256.ico" alt="GSQUART from bmgapp1@gmail.com" height="25" width="25">SQUART</a></h1>
		  <a href="profile.php"><i class="fas fa-user-circle"></i><?=$_SESSION['name']?></a>
		  <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Se déconnecter</a>
		</div>
	  </nav>
	  <div class="content">
	    <h2>GSQUART Home Page</h2>
	    <p>Welcome back, <?=$_SESSION['name']?> ,<?=$_SESSION['matricule']?> !</p>
	  </div>
	  <div class="content">
		<input type="button" value="Générer une ligne." onclick="generer_une_ligneTable()">
		<input type="button" value="Imprimer le Tableau" onclick="Fonc_imprimer('DivTable1')">
	  </div>
	  <!-- P A R T I E   T A B L E -->                                                                             <!-- P A R T I E   T A B L E -->
	  <div class="content" id="DivTable1">
      <table border="5" size=0.4 class="Tableau" id="Table1">
		  <caption id="soustitretab1"> bmgapp1@gmail.com</caption>
		  <thead id="TheadTable1">
              <tr style="background-color:rgb(107,101,141);">
                  <th colspan="1" style="background-color:rgb(197,141,241);"><br />Perso</th>
                  <th colspan="4"><br />Lundi</th>
                  <th colspan="4"><br />Mardi</th>
                  <th colspan="4"><br />Mercredi</th>
                  <th colspan="4"><br />Jeudi</th>
                  <th colspan="4"><br />Vendredi</th>
                  <th colspan="4"><br />Samedi</th>
                  <th colspan="4"><br />Dimanche</th> 
	              <th colspan="4" rowspan="2"><br />Dcmpte H</th>
              </tr>
              <tr>
                  <th> </th>
	              <?php for ($i=1;$i<=7;$i++){ ?>
                  <th><?php if ($HeureDeSubdivisionJournaliere40<10) echo '0';echo $HeureDeSubdivisionJournaliere40.'H-'; ?><br><?php echo $HeureDeSubdivisionJournaliere41.'H'; ?></th>
                  <th><?php if ($HeureDeSubdivisionJournaliere41<10) echo '0'; echo $HeureDeSubdivisionJournaliere41.'H-'; ?><br><?php echo $HeureDeSubdivisionJournaliere42.'H'; ?></th>
                  <th><?php echo $HeureDeSubdivisionJournaliere42.'H-'; ?><br><?php echo $HeureDeSubdivisionJournaliere43.'H'; ?></th>
                  <th><?php echo $HeureDeSubdivisionJournaliere43.'H-'; ?><br><?php echo $HeureDeSubdivisionJournaliere44.'H'; ?></th>
	              <?php } ?>
              </tr>
              <tr>
                  <th colspan="1">Sup</th>
                 <td colspan="28"></td>
		         <td>HTE</td>
		        <td>HNN</td>
		        <td>HJF</td>
		        <td>HNF</td>
              </tr>
		  </thead>
		  <tbody id="TbodyTable1">
		      <?php  for ($lig=0;$lig<30;$lig++){ ?>
              <tr>
                  <td>
                      <SELECT name="Nom" size="1" class="SELECT_LISTEAGENT">
                          <?php  for ($i=0;$i<count($agentp);$i++){ ?> 
                          <OPTION class="OPTION_LISTEAGENT"> <?= $agentp[$i]->mat.' '.$agentp[$i]->nom.' '.$agentp[$i]->prenom; ?>
                          <?php } ?>
                      </SELECT>
                  </td>
				      
                      <?php  for ($col=0;$col<28;$col++){ ?> 
					  
                       <?php echo '<td id="C'.($lig+1).($col+1).'" '.'onclick = "CliquerSurCell()" '. 'class="celluleZoneProg"  ></td>' ?>
                      <?php } ?>
                      <?php  for ($col=28;$col<32;$col++){ ?> 
					  <?php echo '<td id="C'.($lig+1).($col+1).'" '.'onclick = "CliquerSurZoneCal()" '. 'class="celluleZoneCalcul"  >null</td>' ?>
                      
                      <?php } ?>
					  
	          </tr>
			  <?php } ?>
	      </tbody>
		  <tfoot id="TfootTable1">
		       <tr rowspan="4" border="0">
			      <td colspan="33">pieds</td>
		      </tr>
		  </tfoot>
      </table>
	    </div>
	<script>
	document.getElementsById("Table1").appendChild(document.getElementsById("TheadTable1"));
	document.getElementsById("Table1").appendChild(document.getElementsById("TbodyTable1"));
	document.getElementsById("Table1").appendChild(document.getElementsById("TfootTable1"));
	document.getElementsById("DivTable1").appendChild(document.getElementsById("Table1"));
	body.appendChild(document.getElementsById("DivTable1"));
	</script>
		
		
	</body>
</html>