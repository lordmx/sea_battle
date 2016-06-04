<?php

namespace board;

use helpers\HelperBoard;

/**
 * Класс, предназначенный для представления доски в виде матрицы
 *
 * @package board
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class Encoder
{
	/**
	 * @param Board $board
	 * @return array
	 */
	public function asMatrix(Board $board)
	{
		$matrix = HelperBoard::createMatrix($board);

		foreach ($board->getShips() as $ship) {
			for ($i = 0, $decksCount = $ship->getDecksCount(); $i < $decksCount; $i++) {
				$matrix[$ship->getNextX($i)][$ship->getNextY($i)] = $decksCount;
			}
		}

		return $matrix;
	}

	/**
	 * @param Board $board
	 * @return string
	 */
	public function asHash(Board $board)
	{
		return md5(json_encode($this->asMatrix($board)));
	}
}