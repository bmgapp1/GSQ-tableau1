<?php
session_start();
// info connection
/*$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'BAMOGO';
$DATABASE_PASS = 'bamogo';
$DATABASE_NAME = 'ssm2';
*/
/*
$DATABASE_HOST = 'fdb24.awardspace.net';
$DATABASE_USER = '3280433_bamogo';
$DATABASE_PASS = 'bamogo20';
$DATABASE_NAME = '3280433_bamogo';
/**/$DATABASE_HOST = 'remotemysql.com';
$DATABASE_USER = 'QeU06kz7zq';
$DATABASE_PASS = 'TDIrQnWETs';
$DATABASE_NAME = 'QeU06kz7zq';/**/
// Essayez de vous connecter en utilisant les informations ci-dessus.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// S'il y a une erreur avec la connexion, arrêtez le script et affichez l'erreur.
	die ('Impossible de se connecter à MySQL: ' . mysqli_connect_error());
}

// Maintenant, nous vérifions si les données du formulaire de connexion ont été soumises, isset () vérifiera si les données existent.
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Impossible d'obtenir les données qui auraient dû être envoyées.
	die ('Veuillez remplir le champ nom d\'utilisateur et mot de passe!');
}

// Préparez notre SQL, la préparation de l'instruction SQL empêchera l'injection SQL.
if ($stmt = $con->prepare('SELECT matricule, password FROM comptes WHERE username = ?')) {
	// Paramètres de liaison (s = chaîne, i = int, b = blob, etc.), dans notre cas, le nom d'utilisateur est une chaîne, nous utilisons donc "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Stockez le résultat afin que nous puissions vérifier si le compte existe dans la base de données.
	$stmt->store_result();
	
	if ($stmt->num_rows > 0) {
	$stmt->bind_result($matricule, $password);
	$stmt->fetch();
	// Le compte existe, maintenant nous vérifions le mot de passe.
	// Remarque: n'oubliez pas d'utiliser password_hash dans votre fichier d'enregistrement pour stocker les mots de passe hachés.
	 if ($_POST['password'] === $password) {
		// Succès de la vérification! L'utilisateur s'est connecté!
		// Créez des sessions afin que nous sachions que l'utilisateur est connecté, ils agissent essentiellement comme des cookies mais se souviennent des données sur le serveur.
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['matricule'] = $matricule;
		// juste test echo 'Welcome ' . $_SESSION['name'] . '!';
		header('Location: home.php'); //aller à l'acceuil
	 } 
	 else {
		echo 'Mot de passe incorrect!';
	}
  } else {
	echo 'Nom d\'utilisateur incorrect!';
  }
$stmt->close();
}
?>
