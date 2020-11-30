<?php


namespace Madeorsk;

/**
 * Classe des coordonnées GPS.
 * @package Madeorsk
 */
class GpsCoordinates
{
	private string $lat;
	private string $lon;

	/**
	 * Construit une classe des coordonnées GPS.
	 * @param string $lat - La latitude.
	 * @param string $lon - La longitude.
	 */
	public function __construct(string $lat, string $lon)
	{
		// Assignation de la latitude et de la longitude, fournies en argument.
		$this->lat = $lat;
		$this->lon = $lon;
	}

	/**
	 * @return string - La latitude.
	 */
	public function getLatitude(): string
	{
		return $this->lat;
	}
	/**
	 * @return string - La longitude.
	 */
	public function getLongitude(): string
	{
		return $this->lon;
	}
}