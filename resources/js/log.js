/**
 * Контроллер страницы просмотра логов
 */
class Log {

	/**
	 * Наименование приложения, чьи логи будут просматриваться
	 * @type string
	 */
	appName;

	/**
	 * Текущий открытый файл
	 * @type string
	 */
	currentFile;

	/**
	 * Смещение для следующего запроса
	 * @type number
	 */
	offset = 0;

	/** @type jQuery */
	$wrapper;

	/** @type jQuery */
	$content;

	/** @type jQuery */
	$list;

	/**
	 * Текущий открытый файл
	 * @type jQuery
	 */
	$currentFile;

	COLORS = {
		30: 'black',
		31: 'red',
		32: 'green',
		33: 'yellow',
		34: 'blue',
		35: 'magenta',
		36: 'cyan',
		37: 'light_gray',
		38: 'crimson',
		0: 'white'
	};

	constructor () {
		this.appName = $('meta[name=appName]').attr('content');

		this.$wrapper = $('body > .logs');
		this.$content = $('body > .logs > .logs-content');
		this.$list = $('body > .logs > .logs-list');

		this.$list.on('click', '.logs-list-item', e => {
			const $target = $(e.target);

			this.selectItem($target);
			this.updateCurrentFile($target);
			this.updateContent();
		});
	}

	/** Обновляет список файлов */
	updateList () {
		$.ajax({
			url: '/log/' + this.appName + '/list',
			method: 'get',
			dataType: 'json',
			success: files => {
				let html = '';

				for (const file of files) {
					html += '<div class="logs-list-item" data-log="' + file + '">' + file + '</div>';
				}

				this.$list.html(html);
				this.updateCurrentFile();
			}
		});
	}

	/** Обновляет контент */
	updateContent () {
		if (!this.currentFile) {
			return this.$content.html('');
		}

		$.ajax({
			url: '/log/' + this.appName + '/content/' + this.currentFile,
			method: 'get',
			data: { offset: this.offset },
			dataType: 'json',
			success: data => {
				this.offset = data.offset;
				const scrollToBottom = !this.$content.html(); // Если загружаем контент в первый раз - всегда скроллим вниз
				this.$content.append(this.prepareContent(data.content));

				if (data.content.length) {
					this.scrollToBottom(scrollToBottom);
				}
			}
		});
	}

	/**
	 * Подготавливает контент к отображению: заменяет переносы строк на div, заменяет ansi цвета на span
	 * @param {string} content
	 * @return {string}
	 */
	prepareContent (content) {
		for (const code in this.COLORS) {
			content = content.replaceAll(
				'[' + code + 'm',
				'</span><span class="logs-content-' + this.COLORS[code] + '">'
			);
		}

		content = content.replace(/[\r\n]+/g, '</span></div><div><span>');

		return '<div>' + content + '</div>';
	}

	/**
	 * Выбор файла лога
	 * @param {jQuery|string} target
	 */
	selectItem (target) {
		this.currentFile = typeof target === 'string' ? target : target.text();
		this.$content.html('');
		this.offset = 0;
	}

	/**
	 * Обновляет текущий выбранный файл
	 * @param {jQuery|string} [target=this.currentFile]
	 */
	updateCurrentFile (target) {
		if (!target) {
			target = this.currentFile;
		}

		if (this.$currentFile) {
			this.$currentFile.removeClass('active');
		}

		this.$currentFile = typeof target === 'string'
			? this.$list.find('[data-log="' + target + '"]')
			: target;

		this.$currentFile.addClass('active');
	}

	scrollToBottom (firstLoad) {
		const maxHeight = log.$content[0].scrollHeight;
		const height = this.$content.height();
		const scroll = this.$content.scrollTop();

		if (firstLoad || maxHeight - height < scroll + (height * 0.1)) {
			this.$content.scrollTop(maxHeight);
		}
	}

}

log = new Log();
log.updateList();
log.selectItem('last.log');
log.updateContent();

setInterval(() => log.updateContent(), 10000);