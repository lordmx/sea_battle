<?php

namespace gateways;

use board\Board;
use ship\Ship;
use board\Encoder;

/**
 * Объект для работы с данными, представляющий игровую доску с кораблями
 *
 * @package models
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class BoardTableGateway
{
	/**
	 * @var \Doctrine\DBAL\Connection 
	 */
	private $conn;

	/**
	 * @var Encoder
	 */
	private $encoder;

	/**
	 * @param \Doctrine\DBAL\Connection $conn
	 * @param Encoder $encoder
	 */
	public function __construct(\Doctrine\DBAL\Connection $conn, Encoder $encoder) {
		$this->conn = $conn;
		$this->encoder = $encoder;
	}

	/**
	 * @param Board $board
	 * @return int
	 */
	public function insert(Board $board)
	{
		$hash = $this->encoder->asHash($board);
		$exists = $this->findByHash($hash);

		if ($exists) {
			return $exists->getId();
		}

		$this->conn->insert('boards', [
			'hash' => $hash,
			'width' => $board->getWidth(),
			'height' => $board->getHeight()
		]);

		$boardId = $this->conn->lastInsertId('boards_id_seq');
		$board->setId($boardId);

		foreach ($board->getShips() as $ship) {
			$this->insertShip($boardId, $ship);
		}

		return $boardId;
	}

	/**
	 * @param int $id
	 * @return Board|null
	 */
	public function findById($id)
	{
		$sql = 'SELECT * FROM boards WHERE id = ?';

		return $this->extend($this->conn->fetchAssoc($sql, [(int)$id]));
	}

	/**
	 * @param string $hash
	 * @return Board|null
	 */
	public function findByHash($hash)
	{
		$sql = 'SELECT * FROM boards WHERE hash = ?';

		return $this->extend($this->conn->fetchAssoc($sql, [$hash]));
	}

	/**
	 * @param array $data
	 * @return Board
	 */
	private function extend($data)
	{
		if (!$data) {
			return null;
		}

		$board = new Board($data['width'], $data['height']);
		$board->setId($data['id']);

		$sql = 'SELECT * FROM ships WHERE board_id = ?';
		$rows = $this->conn->fetchAll($sql, [(int)$data['id']]);

		foreach ($rows as $row) {
			$ship = new Ship(
				$row['decks_count'],
				$row['x'],
				$row['y'],
				$row['rotate_x'],
				$row['rotate_y']
			);
			$board->add($ship);
		}

		return $board;
	}

	/**
	 * @param int $boardId
	 * @param Board $board
	 */
	private function insertShip($boardId, Ship $ship)
	{
		$this->conn->insert('ships', [
			'board_id' => $boardId,
			'decks_count' => $ship->getDecksCount(),
			'x' => $ship->getX(),
			'y' => $ship->getY(),
			'rotate_x' => $ship->getRotateX(),
			'rotate_y' => $ship->getRotateY()
		]);
	}
}