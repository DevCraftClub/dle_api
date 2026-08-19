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
