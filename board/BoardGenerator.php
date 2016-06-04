<?php

namespace board;

use ship\Ship;
use helpers\HelperBoard;

/**
 * Генератор размещений кораблей на доске
 *
 * @package board
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class BoardGenerator
{
	/**
	 * @param Board $board
	 */
	public function generate(Board $board)
	{
		$matrix = HelperBoard::createMatrix($board);

		for ($i = 3; $i >= 0; $i--) {
			for ($j = 0; $j <= 3 - $i; $j++) {
				$canPlace = false;

				do {
					$x = mt_rand(0, $board->getWidth() + 1);
					$y = mt_rand(0, $board->getHeight() + 1);
					
					$rotateX = mt_rand(0, 1);
					$rotateY = 0;

					if ($rotateX == 0) {
						$rotateY = 1;
					}

					$canPlace = true;
					$ship = new Ship($i + 1, $x, $y, $rotateX, $rotateY);
					$canPlace = $this->canPlace($board, $ship, $matrix);

					if ($canPlace) {
						$board->add($ship);
						$matrix = $this->place($ship, $matrix);
					}

				} while (!$canPlace);
			}
		}
	}

	/**
	 * @param Ship $ship
	 * @param array $matrix
	 * @return array
	 */
	private function place(Ship $ship, array $matrix)
	{
		for ($i = 0; $i <= $ship->getDecksCount(); $i++) {
			$matrix[$ship->getNextX($i)][$ship->getNextY($i)] = $ship->getDecksCount();
		}

		return $matrix;
	}

	/**
	 * @param Board $board
	 * @param Ship $ship
	 * @param array $matrix
	 * @return bool
	 */
	private function canPlace(Board $board, Ship $ship, array $matrix)
	{
		$width = $board->getWidth();
		$height = $board->getHeight();

		for ($i = 0; $i <= $ship->getDecksCount(); $i++) {
			$x = $ship->getNextX($i);
			$y = $ship->getNextY($i);

			if (!$this->isFree($matrix, $x, $y, $width, $height)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param array $matrix
	 * @param int $x
	 * @param int $y
	 * @param int $width
	 * @param int $height
	 * @return bool
	 */
	private function isFree(array $matrix, $x, $y, $width, $height)
	{
		$single = $this->getSingle();

		if (
			$x >= 0 &&
			$x < $width &&
			$y >= 0 &&
			$y < $height &&
			$matrix[$x][$y] == 0
		) {
			for ($i = 0, $count = count($single); $i < $count; $i++) {
				$dx = $x + $single[$i][0];
				$dy = $y + $single[$i][1];

				if (
					$dx >= 0 &&
					$dx < $width &&
					$dy >= 0 &&
					$dy < $height &&
					$matrix[$dx][$dy] > 0
				) {
					return false;
				}
			}
		} else {
			return false;
		}

		return true;
	}

	/**
	 * @return array
	 */
	private function getSingle()
	{
		return [
			[0, 1],
			[1, 0],
			[0, -1],
			[-1, 0],
			[1, 1],
			[-1, 1],
			[1, -1],
			[-1, -1],
		];
	}
}