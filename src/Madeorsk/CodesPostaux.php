<?php


namespace Madeorsk;

/**
 * Classe des codes postaux indexés.
 * @package Madeorsk
 */
class CodesPostaux
{
	/**
	 * Chemin var défaut vers le fichier des codes postaux indexés.
	 */
	const DEFAULT_FILEPATH = __DIR__."/../../resources/codes_postaux.json.gz";

	/**
	 * Chemin vers le fichier contenant les codes postaux indexés.
	 * @var string|null
	 */
	protected ?string $filepath = null;

	/**
	 * Données sur les codes postaux indexés.
	 * @var array|null
	 */
	protected ?array $codesPostaux = null;

	/**
	 * Construit la classe des codes postaux à partir du fichier des codes postaux donné.
	 * @param string|null $filepath - Le chemin vers le fichier des codes postaux indexés.
	 */
	public function __construct(?string $filepath = null)
	{
		// Assignation du chemin vers le fichier des codes postaux indexés.
		$this->filepath = $filepath;

		// Chargement du fichier en mémoire.
		$this->load();
	}

	/**
	 * Récupère le chemin vers le fichier des codes postaux.
	 * @return string|null - Le chemin vers le fichier des codes postaux.
	 */
	public function getFilePath()
	{
		return !empty($this->filepath) ? $this->filepath : self::DEFAULT_FILEPATH;
	}

	/**
	 * Charge le fichier des codes postaux en mémoire.
	 */
	public function load()
	{
		// Charge le fichier JSON compressé des codes postaux indexés dans le tableau associatif des codes postaux indexés.
		$this->codesPostaux = json_decode(gzdecode(file_get_contents($this->getFilePath())), true);
	}

	/**
	 * Récupère les codes postaux descendants d'un niveau spécifique.
	 * @param array $level - Un niveau du tableau des codes postaux indexés.
	 * @return CodePostal[] - Les codes postaux descendants du niveau donné.
	 */
	protected function levelToCodesPostaux(array $level): array
	{
		$codesPostaux = []; // Initialisation du tableau des codes postaux.

		foreach ($level as $node)
		{ // Pour chaque noeud descendant, on récupère ses codes postaux.
			if (!empty($node))
			{ // Le noeud n'est pas vide, il y a des choses à faire avec.
				if (isset($node[0]))
				{ // Le noeud n'a pas de nom, c'est une liste de communes pour un code postal spécifique,
					// on en construit la liste.
					foreach ($node as $commune_data)
					{ // Pour chaque commune du code postal, on l'ajoute au code postal correspondant.
						if (empty($codesPostaux[$commune_data["code_postal"]]))
							// On crée un nouvel objet de code postal s'il n'existe pas.
							$codesPostaux[$commune_data["code_postal"]] = new CodePostal($commune_data["code_postal"]);

						$codePostal = $codesPostaux[$commune_data["code_postal"]]; // Récupération du code postal de la commune.
						// Ajout de la commune courante au code postal récupéré.
						$codePostal->addCommune(new Commune($codePostal, $commune_data));
					}
				}
				else // Les descendants ne sont pas indexés par un nom, ce n'est pas une feuille, on descend récursivement.
					$codesPostaux = array_merge($codesPostaux, $this->levelToCodesPostaux($node));
			}
		}

		return $codesPostaux; // Renvoie la liste des codes postaux descendants.
	}

	/**
	 * Retourne la liste des codes postaux commençant par le début passé en paramètre.
	 * @param string $start - Le début du code postal.
	 * @return CodePostal[] - Le tableau des codes postaux commençant par start.
	 */
	public function startingWith(string $start): array
	{
		if (strlen($start) >= 5)
			// La longueur de la chaine est de 5 au moins, on a donc un code postal complet, on le récupère directement.
			return !empty($code_postal = $this->get($start)) ? [ $code_postal ] : [];

		// On découpe la chaine de début du code postal.
		$start = str_split($start);

		$current_level = $this->codesPostaux;
		while (isset($start[0]))
		{ // Tant qu'on peut encore avancer dans l'arbre...
			if (!empty($current_level["_{$start[0]}"]))
				$current_level = $current_level["_{$start[0]}"];
			else // On ne trouve pas le noeud suivant dans l'arbre, le début du code postal ne correspond à aucun code.
				return []; // Tableau vide, aucun code postal ne correspond.

			array_shift($start); // On retire le premier élément du tableau du début du code postal, exploré.
		}

		// On a exploré tout le tableau du début du code postal.
		// On peut convertir le niveau actuel en liste de codes postaux.
		return $this->levelToCodesPostaux($current_level);
	}

	/**
	 * Obtient les données du code postal passé en paramètre.
	 * @param string $code_postal_str - Le code postal pour lequel récupérer les données.
	 * @return CodePostal|null - Les données du code postal correspondant.
	 */
	public function get(string $code_postal_str): ?CodePostal
	{
		// On découpe le code postal pour récupérer la liste des codes postaux commençant comme lui
		// (le dernier caractère n'étant pas indexé, on ne peut pas récupérer directement un code postal commençant par 5 chiffres).
		$code_postal_start = substr($code_postal_str, 0, 4);
		$codes_postaux = $this->startingWith($code_postal_start);

		foreach ($codes_postaux as $code_postal)
			// Pour chaque code postal récupéré, on regarde s'il correspond à celui cherché.
			if ($code_postal->getCode() == $code_postal_str)
				// Si le code postal courant est celui cherché, on le retourne directement.
				return $code_postal;

		return null; // On n'a trouvé aucun code postal correspondant à celui cherché.
	}
}
