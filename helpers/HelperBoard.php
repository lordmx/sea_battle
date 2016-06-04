<?php

namespace helpers;

use board\Board;

/**
 * Хелпер для работы с игровой доской
 * 
 * @package helpers
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class HelperBoard
{
	/**
	 * @param Board $board
	 * @return array
	 */
	public static function createMatrix(Board $board)
	{
		$matrix = [];

		for ($i = 0; $i < $board->getWidth(); $i++) {
			$matrix[$i] = [];

			for ($j = 0; $j < $board->getHeight(); $j++) {
				$matrix[$i][$j] = 0;
			}
		}

		return $matrix;
	}
}