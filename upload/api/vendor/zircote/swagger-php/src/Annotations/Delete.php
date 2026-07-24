<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

/**
 * @Annotation
 */
class Delete extends Operation
{
    /**
     * @var string
     */
    public $method = 'delete';

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
class Delete extends Operation
{
    /**
     * @inheritdoc
     */
    public $method = 'delete';

    /**
     * @inheritdoc
     */
    public static $_parents = [
        PathItem::class,
    ];
}
>>>>>>> Current commit: Начало обновления до api v2
