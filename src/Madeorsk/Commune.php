<?php


namespace Madeorsk;

/**
 * Classe représentant une commune d'un code postal.
 * @package Madeorsk
 */
class Commune
{
	private string $code;
	private string $nom;
	private CodePostal $codePostal;
	private string $ligne5;
	private string $libAcheminement;
	private ?GpsCoordinates $gpsCoordinates;

	/**
	 * Construit un nouvel objet représentant une commune.
	 * @param CodePostal $codePostal - L'objet du code postal de la commune.
	 * @param array $data - Les données sur la commune.
	 */
	public function __construct(CodePostal $codePostal, array $data)
	{
		// Assignation du code postal.
		$this->codePostal = $codePostal;

		// Assignation des informations sur la commune, récupérées depuis les données obtenues du JSON.
		$this->code = $data["code_commune"];
		$this->nom = $data["nom_commune"];
		$this->ligne5 = $data["ligne_5"];
		$this->libAcheminement = $data["libelle_acheminement"];

		if (!empty($data["coordonnees_gps"]))
			// Création des coordonnées GPS si elles existent.
			$this->gpsCoordinates = new GpsCoordinates($data["coordonnees_gps"]["lat"], $data["coordonnees_gps"]["lon"]);
	}

	/**
	 * @return string - Le code INSEE de la commune.
	 */
	public function getCode(): string
	{
		return $this->code;
	}

	/**
	 * @return string - Le nom de la commune.
	 */
	public function getNom(): string
	{
		return $this->nom;
	}

	/**
	 * @return string - La ligne 5 de l'adresse, pour la commune.
	 */
	public function getLigne5(): string
	{
		return $this->ligne5;
	}

	/**
	 * @return string - Le libellé d'acheminement de la commune.
	 */
	public function getLibAcheminement(): string
	{
		return $this->libAcheminement;
	}

	/**
	 * @return GpsCoordinates - Les coordonnées GPS de la commune.
	 */
	public function getGpsCoordinates(): GpsCoordinates
	{
		return $this->gpsCoordinates;
	}
}