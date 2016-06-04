<?php

namespace ship;

/**
 * Объект корабля на доске
 *
 * @package ship
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class Ship
{
	/**
	 * @var int
	 */
	private $decksCount;

	/**
	 * @var int
	 */
	private $x;

	/**
	 * @var int
	 */
	private $y;

	/**
	 * @var int
	 */
	private $rotateX;

	/**
	 * @var int
	 */
	private $rotateY;

	/**
	 * @param int $decksCount
	 * @param int $x
	 * @param int $y
	 * @param int $rotateX
	 * @param int $rotateY
	 */
	public function __construct($decksCount, $x, $y, $rotateX, $rotateY)
	{
		$this->decksCount = $decksCount;
		$this->x = $x;
		$this->y = $y;
		$this->rotateX = $rotateX;
		$this->rotateY = $rotateY;
	}

	/**
	 * @return int
	 */
	public function getDecksCount()
	{
		return $this->decksCount;
	}

	/**
	 * @return int
	 */
	public function getX()
	{
		return $this->x;
	}

	/**
	 * @return int
	 */
	public function getY()
	{
		return $this->y;
	}

	/**
	 * @return int
	 */
	public function getRotateX()
	{
		return $this->rotateX;
	}

	/**
	 * @return int
	 */
	public function getRotateY()
	{
		return $this->rotateY;
	}

	/**
	 * @param int $index
	 * @return int
	 */
	public function getNextX($index)
	{
		$this->ensureIndexIsValid($index);

		return $this->x + $index * $this->rotateX;
	}

	/**
	 * @param int $index
	 * @return int
	 */
	public function getNextY($index)
	{
		$this->ensureIndexIsValid($index);

		return $this->y + $index * $this->rotateY;
	}

	/**
	 * @param int $index
	 * @throws \Exception
	 */
	private function ensureIndexIsValid($index)
	{
		if ($index < 0) {
			throw new \Exception('The index must be greater than 0');
		}

		if ($index > $this->decksCount) {
			throw new \Exception('The index must be lesser than ' . ($this->decksCount - 1));
		}
	}
}