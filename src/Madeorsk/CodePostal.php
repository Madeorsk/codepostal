<?php


namespace Madeorsk;


/**
 * Classe représentant les informations sur un code postal.
 * @package Madeorsk
 */
class CodePostal
{
	/**
	 * Le code postal en question.
	 * @var string
	 */
	private string $code;

	/**
	 * La liste des communes dans ce code postal.
	 * @var Commune[]
	 */
	private array $communes;

	/**
	 * Construit un objet représentant un code postal.
	 */
	public function __construct(string $code)
	{
		$this->code = $code;
	}

	/**
	 * @return string - Le code postal.
	 */
	public function getCode(): string
	{
		return $this->code;
	}

	/**
	 * Ajoute une commune dans le code postal en question.
	 * @param Commune $commune - La commune à ajouter.
	 */
	public function addCommune(Commune $commune)
	{
		$this->communes[] = $commune;
	}
	/**
	 * Renvoie la liste des communes du code postal.
	 * @return Commune[] - Les communes du code postal.
	 */
	public function getCommunes(): array
	{
		return $this->communes;
	}
}