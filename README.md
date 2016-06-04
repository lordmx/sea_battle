# Морской бой

** Тестовое задание

## Миграция БД

* Создание БД

```
CREATE DATABASE sea_battle;
```

* Создание таблица

```
CREATE TABLE boards (
	id SERIAL PRIMARY KEY,
	hash VARCHAR(32) UNIQUE,
	width INT,
	height INT
);

CREATE TABLE ships(
	id SERIAL,
	board_id INT FOREIGN KEY REFERENCES boards ON DELETE CASCADE,
	x INT,
	y INT,
	decks_count INT,
	rotate_x INT,
	rotate_y INT
);
```

## Установка приложения

```
$ composer install
```