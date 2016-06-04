function Board() {
	Board.prototype.getContainer = function() {
		return $('#board');
	};

	Board.prototype.fetch = function(id) {
		var that = this, url = '/fetch';

		if (id) {
			url += '/' + id;
		}

		$.ajax({
			method: 'get',
			url: url,
			dataType: 'json'
		}).done(function (data) {
			if (data) {
				$('h1 span').text(data.id);

				if (id != '' && typeof window.history != 'undefined') {
					var title = 'Морской бой: тестовой задание, #';
					history.replaceState({} , title + data.id, '/' + data.id);
				}

				that.display(eval('(' + data.board + ')'), data.width, data.height);
			}
		});
	};

	Board.prototype.display = function(board, width, height) {
		var container = this.getContainer(); 
		container.empty();

		for (var i = 0; i < width; i++) {
			$('<div class="cf"></div>').appendTo(container);

			for (var j = 0; j < height; j++) {
				var cell = $('<div class="cell"></div>');

				if (board[i][j] > 0) {
					cell.addClass('ship');
					$('<span>' + board[i][j] + '</div>').appendTo(cell);
				}

				cell.appendTo(container);
			}
		}
	};
};