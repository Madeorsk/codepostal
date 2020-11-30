<?php

// Fichier CSV de l'INSEE à utiliser pour générer le fichier PHP.
$filename = __DIR__."/laposte_hexasmal.csv";

if ($argc > 1)
	// Si le nom du fichier est passé en argument, on le lit.
	$filename = $argv[1];

echo "Ouverture du fichier.\n";
$file = fopen($filename, "r+");

$codes_postaux = [];

echo "Lecture de la première ligne.\n";
fgets($file); // On lit la première ligne sans la traiter, c'est la ligne de titre.
echo "Début de la lecture du fichier CSV...\n";
while (!empty($row = fgetcsv($file, 0, ";")))
{ // Pour chaque ligne du CSV lue.
	$coordonnees_gps = explode(",", $row[5]);
	// On place les données dans le tableau en fonction du code postal.
	add($row[2], [
		"code_commune" => $row[0],
		"nom_commune" => $row[1],
		"code_postal" => $row[2],
		"ligne_5" => $row[3],
		"libelle_acheminement" => $row[4],
		"coordonnees_gps" => !empty($coordonnees_gps[0]) ? [
			"lat" => $coordonnees_gps[0],
			"lon" => $coordonnees_gps[1],
		] : null,
	]);
}

echo "Fermeture du fichier.\n";
fclose($file);

echo "Sauvegarde dans le fichier JSON indexé.\n";
// Exportation des données dans un fichier JSON compressé.
file_put_contents(__DIR__."/resources/codes_postaux.json.gz", gzencode(json_encode($codes_postaux)));

function add($code_postal, $data)
{
	global $codes_postaux; // On utilise la variable globale stockant les codes postaux.

	// On va indexer par chaque numéro du code postal donné.
	$code_postal = str_split($code_postal);
	$current_level = &$codes_postaux; // Initialisation du parcours de l'indexation de codes_postaux.
	while (isset($code_postal[0]))
	{ // On descend dans l'arborescence pour trouver la feuille contenant les informations sur le code postal courant.
		if (empty($current_level["_{$code_postal[0]}"]))
			// On crée un nouveau noeud de l'arbre pour le numéro courant du code postal, s'il n'existe pas.
			$current_level["_{$code_postal[0]}"] = [];

		// Le niveau actuel est le noeud de l'arbre correspondant au numéro courant.
		$current_level = &$current_level["_{$code_postal[0]}"];

		array_shift($code_postal); // On retire le numéro courant du tableau du code postal.
	}

	// On ajoute les données dans la feuille de l'arbre des codes postaux.
	$current_level[] = $data;
}
