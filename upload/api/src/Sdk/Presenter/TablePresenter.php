<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

/**
 * Универсальный презентер произвольной таблицы.
 */
final class TablePresenter extends AbstractTablePresenter {
	public function __construct(private string $tableName) {
		parent::__construct($tableName);
	}

	public function table(): string {
		return $this->tableName;
	}
}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Sdk\Presenter;

/**
 * Универсальный презентер произвольной таблицы.
 */
final class TablePresenter extends AbstractTablePresenter {
	public function __construct(private string $tableName) {
		parent::__construct($tableName);
	}

	protected function table(): string {
		return $this->tableName;
	}
}
>>>>>>> Current commit: Начало обновления до api v2
