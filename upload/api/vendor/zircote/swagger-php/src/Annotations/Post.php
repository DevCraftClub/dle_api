<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

/**
 * @Annotation
 */
class Post extends Operation
{
    /**
     * @var string
     */
    public $method = 'post';

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
class Post extends Operation
{
    /**
     * @inheritdoc
     */
    public $method = 'post';

    /**
     * @inheritdoc
     */
    public static $_parents = [
        PathItem::class,
    ];
}
>>>>>>> Current commit: Начало обновления до api v2
