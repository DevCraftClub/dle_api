<<<<<<< New base: Update README.md
<?php declare(strict_types=1);
/**
 * @license Apache 2.0
 */

namespace OpenApi\Attributes;

use OpenApi\Annotations as OA;
use OpenApi\Undefined;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Header extends OA\Header
{
    /**
     * @param string|class-string|object|null $ref
     * @param array<string,mixed>|null        $x
     * @param list<Attachable>|null           $attachables
     */
    public function __construct(
        string|object|null $ref = null,
        ?string $header = null,
        ?string $description = Undefined::UNDEFINED,
        ?bool $required = null,
        ?Schema $schema = null,
        ?bool $deprecated = null,
        ?bool $allowEmptyValue = null,

        // abstract annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct([
            'ref' => $ref ?? Undefined::UNDEFINED,
            'header' => $header ?? Undefined::UNDEFINED,
            'description' => $description,
            'required' => $required ?? Undefined::UNDEFINED,
            'deprecated' => $deprecated ?? Undefined::UNDEFINED,
            'allowEmptyValue' => $allowEmptyValue ?? Undefined::UNDEFINED,
            'x' => $x ?? Undefined::UNDEFINED,
            'attachables' => $attachables ?? Undefined::UNDEFINED,
            'value' => $this->combine($schema),
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

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Header extends OA\Header
{
    /**
     * @param string|class-string|object|null $ref
     * @param array<string,mixed>|null        $x
     * @param Attachable[]|null               $attachables
     */
    public function __construct(
        string|object|null $ref = null,
        ?string $header = null,
        ?string $description = Generator::UNDEFINED,
        ?bool $required = null,
        ?Schema $schema = null,
        ?bool $deprecated = null,
        ?bool $allowEmptyValue = null,
        // annotation4
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct([
            'ref' => $ref ?? Generator::UNDEFINED,
            'header' => $header ?? Generator::UNDEFINED,
            'description' => $description,
            'required' => $required ?? Generator::UNDEFINED,
            'deprecated' => $deprecated ?? Generator::UNDEFINED,
            'allowEmptyValue' => $allowEmptyValue ?? Generator::UNDEFINED,
            'x' => $x ?? Generator::UNDEFINED,
            'attachables' => $attachables ?? Generator::UNDEFINED,
            'value' => $this->combine($schema),
        ]);
    }
}
>>>>>>> Current commit: Начало обновления до api v2
