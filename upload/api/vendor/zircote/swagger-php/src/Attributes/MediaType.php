<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Attributes;

use OpenApi\Annotations as OA;
use OpenApi\Undefined;

#[\Attribute(\Attribute::TARGET_CLASS)]
class MediaType extends OA\MediaType
{
    /**
     * @param array<Examples>          $examples
     * @param list<Encoding>           $encoding
     * @param array<string,mixed>|null $x
     * @param list<Attachable>|null    $attachables
     */
    public function __construct(
        ?string $mediaType = null,
        ?Schema $schema = null,
        mixed $example = Undefined::UNDEFINED,
        ?array $examples = null,
        ?array $encoding = null,

        // abstract annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct([
                'mediaType' => $mediaType ?? Undefined::UNDEFINED,
                'example' => $example,
                'x' => $x ?? Undefined::UNDEFINED,
                'attachables' => $attachables ?? Undefined::UNDEFINED,
                'value' => $this->combine($schema, $examples, $encoding),
            ]);
    }
}
|||||||
=======
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Attributes;

use OpenApi\Annotations as OA;
use OpenApi\Generator;

#[\Attribute(\Attribute::TARGET_CLASS)]
class MediaType extends OA\MediaType
{
    /**
     * @param array<Examples>          $examples
     * @param Encoding[]               $encoding
     * @param array<string,mixed>|null $x
     * @param Attachable[]|null        $attachables
     */
    public function __construct(
        ?string $mediaType = null,
        ?Schema $schema = null,
        mixed $example = Generator::UNDEFINED,
        ?array $examples = null,
        ?array $encoding = null,
        // annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct([
                'mediaType' => $mediaType ?? Generator::UNDEFINED,
                'example' => $example,
                'x' => $x ?? Generator::UNDEFINED,
                'attachables' => $attachables ?? Generator::UNDEFINED,
                'value' => $this->combine($schema, $examples, $this->encodingCompat(
                    $encoding,
                    fn (array $args): Encoding => new Encoding(...$args),
                )),
            ]);
    }
}
>>>>>>> Current commit: Начало обновления до api v2
