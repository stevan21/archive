
<?php
if (isset($_POST['btn-archiver'])) {
    // Connexion à la base de données
    $con = mysqli_connect("localhost", "root", "", "arch");

    // Récupération des données dans le formulaire
    $titre = $_POST['titre'];
    $type = $_POST['type'];
    $dates = $_POST['date'];

    if (!empty($titre) && !empty($type) && !empty($dates)) {
        // Vérifier si l'archive existe déjà
        $stmt1 = $con->prepare("SELECT titre, type, date FROM archive WHERE titre = ? AND type = ? AND date = ?");
        $stmt1->bind_param("sss", $titre, $type, $dates);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($result1->num_rows > 0) {
            // Si l'archive existe déjà
            $message = '<p style="color:#ff8000">L\'archive existe déjà</p>';
        } else {
            // Si non
            if (isset($_FILES['file'])) {
                // Si une image a été téléchargée
                $img_nom = $_FILES['file']['name'];
                // On récupère le nom de l'image
                $tmp_nom = $_FILES['file']['tmp_name'];
                // Nous définissons un nom temporaire
                $time = time();
                // On récupère l'heure actuelle
                // On renome l'image
                $nouveau_nom_img = $time . $img_nom;
                // On déplace l'image dans un nouveau dossier images-archives
                $deplacer_image = move_uploaded_file($tmp_nom, "images-archives/" . $nouveau_nom_img);

                if ($deplacer_image) {
                    // Si l'image a été déplacée
                    // Inserons le titre, la date, type
                    $stmt2 = $con->prepare("INSERT INTO archive (titre, type, date, image) VALUES (?, ?, ?, ?)");
                    $stmt2->bind_param("ssss", $titre, $type, $dates, $nouveau_nom_img);
                    $stmt2->execute();

                    if ($stmt2->affected_rows > 0) {
                        // Si les informations ont été intégrées dans la base de données
                        $message = '<p style="color:#008000">Archive effectuée!</p>';
                    } else {
                        // Si non
                        $message = '<p style="color:#ff8000">L\'archive n\'a pas été effectuée!</p>';
                    }
                }
            }
        }
    } else {
        // Si non tous les champs ne sont pas remplis
        $message = '<p style="color:#ff8000">Veuillez remplir tous les champs !</p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Archivage de fichiers</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Archivage de fichiers</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="message">
            <?php if (isset($message)) {
                // Si la variable message existe on ajout le contenu de la variable
                echo $message;
            } ?>
        </div>
        <label for="file">Fichier :</label>
        <input type="file" id="file" name="file" required><br><br>
        <label for="type">Type de fichier :</label>
        <select id="type" name="type">
            <option value="pdf">PDF</option>
            <option value="video">Vidéo</option>
            <option value="image">Image</option>
            <option value="word">Word</option>
        </select><br><br>
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" required><br><br>
        <label for="date">Date d'enregistrement :</label>
        <input type="date" id="date" name="date" required><br><br>
        <input type="submit" value="Archiver" name="btn-archiver">
        <a href="recherche.php">Voir les archives</a>
    </form>
</body>
</html>
