# CodePostal

Liste des codes postaux français en une dépendance Composer.

## Installation

Vous pouvez installer cette bibliothèque à partir de Composer.

```shell script
composer require madeorsk/codepostal
```

## Utilisation

Voici un rapide exemple commenté pour démarrer l'utilisation de la bibliothèque CodePostal.

```php
<?php
require __DIR__."/vendor/autoload.php";

use Madeorsk\CodesPostaux;

$cp = new CodesPostaux(); // Initialisation de la bibliothèque des codes postaux et chargement de la liste en mémoire.

$codesPostaux = $cp->startingWith("575"); // Récupère les codes postaux commençant par "575".

foreach ($codesPostaux as $codePostal)
{ // Pour chaque code postal récupéré...
	// ... on affiche le code postal et le nombre de communes de ce code postal.
	echo "{$codePostal->getCode()} (".count($codePostal->getCommunes())." communes)\n";

	foreach ($codePostal->getCommunes() as $commune)
	{ // Pour chaque commune de ce code postal...
		// ... on affiche son nom et ses coordonnées GPS.
		echo " - {$commune->getNom()} (".
			(empty($commune->getGpsCoordinates())
				? "pas de coordonnées GPS" // La commune n'a pas de coordonnées GPS enregistrées.
				: "{$commune->getGpsCoordinates()->getLatitude()}, {$commune->getGpsCoordinates()->getLongitude()}")
			.")\n";
	}

	echo "\n"; // On saute une ligne.
}
```

Les classes sont entièrement commentées et documentées.
