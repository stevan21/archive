
<!DOCTYPE html>
<html>
<head>
	<title>Recherche de fichiers</title>
	<link rel="stylesheet" href="recherche.css">
</head>

<body>


    <nav class="navbar">
		<img src="image-gauche.png" alt="logo">
		<button>Déconnexion</button>
		<img src="images/moi.jfif" alt="stevan ">
	</nav>

	<h1 class="initial-elements">Recherche de fichiers</h1>
	<form class="search-form initial-elements">
		<input type="search" name="search" placeholder="Rechercher un fichier...">
		<button type="submit">Rechercher</button>
		<button type="button" id="close-search" style="display: none;">Fermer la recherche</button>
	</form>

	

	<div class="container">
		<table>
			<tr id="items">
				<th>image</th>
				<th>Titre</th>
				<th>type de fichier</th>
				<th>date d'ajout</th>
				<th>voir</th>
				<th>telecharger</th>
				<th>imprimer</th>
			</tr>
			<?php
			// Connexion à la base de données
			$con = mysqli_connect("localhost", "root", "", "arch");
			if (!$con) {
				die("Erreur de connexion : " . mysqli_connect_error());
			}
			
			// Récupérer la saisie de l'utilisateur
			if (isset($_GET['search'])) {
				$saisie = $_GET['search'];
			} else {
				$saisie = '';
			}
			
			// Requête SQL pour filtrer les résultats
			if (!empty($saisie)) {
				$req3 = mysqli_query($con, "SELECT * FROM archive WHERE titre LIKE '%$saisie%' OR type LIKE '%$saisie%'");
			} else {
				$req3 = mysqli_query($con, "SELECT * FROM archive");
			}
			
			if (mysqli_num_rows($req3) == 0) {
				echo "aucune archive trouvé!";
			} else {
				while ($row = mysqli_fetch_assoc($req3)) {
					echo "<tr>
						<td><img src='images-archives/" . $row['image'] . "' alt=''></td>
						<td>" . $row['titre'] . "</td>
						<td>" . $row['type'] . "</td>
						<td>" . $row['date'] . "</td>
						<td><button class='download-btn'>voir</button></td>
						<td><button class='download-btn'>Télécharger</button></td>
						<td><button class='download-btn'>imprimer</button></td>
					</tr>";
				}
			}
			?>
		</table>
	</div>
	
	<script>
		const closeSearchButton = document.getElementById('close-search');
		const container = document.querySelector('.container');
		const searchForm = document.querySelector('.search-form');
		const initialElements = document.querySelectorAll('.initial-elements');
		
		closeSearchButton.addEventListener('click', () => {
			container.style.display = 'none';
			searchForm.reset(); // Réinitialiser le formulaire de recherche
			initialElements.forEach(element => element.style.display = 'block'); // Réafficher les éléments initiaux
			closeSearchButton.style.display = 'none'; // Cacher le bouton "Fermer la recherche"
		});
		
		// Afficher les résultats de la recherche lorsque le formulaire est soumis
		searchForm.addEventListener('submit', () => {
			container.style.display = 'block';
			initialElements.forEach(element => element.style.display = 'none'); // Cacher les éléments initiaux
			closeSearchButton.style.display = 'block'; // Afficher le bouton "Fermer la recherche"
		});
	</script>
</body>
</html>