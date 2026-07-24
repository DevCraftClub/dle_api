<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Attributes;

use OpenApi\Annotations as OA;
use OpenApi\Undefined;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Encoding extends OA\Encoding
{
    /**
     * @param list<Header>             $headers
     * @param array<string,mixed>|null $x
     * @param list<Attachable>|null    $attachables
     */
    public function __construct(
        ?string $property = null,
        ?string $contentType = null,
        ?array $headers = null,
        ?string $style = null,
        ?bool $explode = null,
        ?bool $allowReserved = null,

        // abstract annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct([
            'property' => $property ?? Undefined::UNDEFINED,
            'contentType' => $contentType ?? Undefined::UNDEFINED,
            'style' => $style ?? Undefined::UNDEFINED,
            'explode' => $explode ?? Undefined::UNDEFINED,
            'allowReserved' => $allowReserved ?? Undefined::UNDEFINED,
            'x' => $x ?? Undefined::UNDEFINED,
            'attachables' => $attachables ?? Undefined::UNDEFINED,
            'value' => $this->combine($headers),
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
class Encoding extends OA\Encoding
{
    /**
     * @param Header[]                 $headers
     * @param array<string,mixed>|null $x
     * @param Attachable[]|null        $attachables
     */
    public function __construct(
        ?string $property = null,
        ?string $contentType = null,
        ?array $headers = null,
        ?string $style = null,
        ?bool $explode = null,
        ?bool $allowReserved = null,
        // annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct([
            'property' => $property ?? Generator::UNDEFINED,
            'contentType' => $contentType ?? Generator::UNDEFINED,
            'style' => $style ?? Generator::UNDEFINED,
            'explode' => $explode ?? Generator::UNDEFINED,
            'allowReserved' => $allowReserved ?? Generator::UNDEFINED,
            'x' => $x ?? Generator::UNDEFINED,
            'attachables' => $attachables ?? Generator::UNDEFINED,
            'value' => $this->combine($headers),
        ]);
    }
}
>>>>>>> Current commit: Начало обновления до api v2
