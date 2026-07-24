<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

use OpenApi\Annotations as OA;

/**
 * A <code>@OA\Request</code> path parameter.
 *
 * @Annotation
 */
class PathParameter extends Parameter
{
    /**
     * @var string
     */
    public $in = 'path';

    /**
     * @var bool
     */
    public $required = true;

    /**
     * @inheritdoc
     */
    public static $_required = ['name'];
}
|||||||
=======
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

use OpenApi\Annotations as OA;

/**
 * A <code>@OA\Request</code> path parameter.
 *
 * @Annotation
 */
class PathParameter extends Parameter
{
    /**
     * @inheritdoc
     * This takes 'path' as the default location.
     */
    public $in = 'path';

    /**
     * @inheritdoc
     */
    public $required = true;

    /**
     * @inheritdoc
     */
    public static $_required = ['name'];
}
>>>>>>> Current commit: Начало обновления до api v2
