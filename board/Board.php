<?php

namespace board;

use ship\Ship;

/**
 * Объект игровой доски. На нем расставляются корабли.
 *
 * @package board
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class Board
{
	const DEFAULT_SIDE = 10;

	/**
	 * @var int
	 */
	private $width;

	/**
	 * @var int
	 */
	private $height;

	/**
	 * @return Ship[]
	 */
	private $ships = [];

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @param int $width
	 * @param int $height
	 */
	public function __construct($width = self::DEFAULT_SIDE, $height = self::DEFAULT_SIDE)
	{
		$this->width = $width;
		$this->height = $height;
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @return int
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * @param Ship $ship
	 */
	public function add(Ship $ship)
	{
		$this->ships[] = $ship;
	}

	/**
	 * @return Ships[]
	 */
	public function getShips()
	{
		return $this->ships;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}
}