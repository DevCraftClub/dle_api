<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

/**
 * @Annotation
 */
class Get extends Operation
{
    /**
     * @var string
     */
    public $method = 'get';

    /**
     * @inheritdoc
     */
    public static $_parents = [
        PathItem::class,
    ];
}
|||||||
=======
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

/**
 * @Annotation
 */
class Get extends Operation
{
    /**
     * @inheritdoc
     */
    public $method = 'get';

    /**
     * @inheritdoc
     */
    public static $_parents = [
        PathItem::class,
    ];
}
>>>>>>> Current commit: Начало обновления до api v2
