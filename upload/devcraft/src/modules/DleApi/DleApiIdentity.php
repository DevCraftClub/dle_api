<?php

declare(strict_types=1);

namespace DevCraft\Modules\DleApi;

use DevCraft\Core\Abstracts\AbstractModuleIdentity;

/**
 * Identity модуля DLE API.
 *
 * MODULE/CODE = DLE mod (`engine/inc/dleapi.php` → `?mod=dleapi`).
 * Каталог модуля: `devcraft/src/modules/DleApi/`.
 */
final class DleApiIdentity extends AbstractModuleIdentity {

	public const string MODULE = 'dleapi';

	public const string CODE = 'dleapi';

}
