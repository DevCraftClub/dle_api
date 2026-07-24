<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

/**
 * @since OpenAPI 3.2.0
 *
 * @Annotation
 */
class Query extends Operation
{
    /**
     * @var string
     */
    public $method = 'query';

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
 * @since OpenAPI 3.2.0
 *
 * @Annotation
 */
class Query extends Operation
{
    /**
     * @inheritdoc
     */
    public $method = 'query';

    /**
     * @inheritdoc
     */
    public static $_parents = [
        PathItem::class,
    ];
}
>>>>>>> Current commit: Начало обновления до api v2
