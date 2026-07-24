<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Attributes;

use OpenApi\Annotations as OA;
use OpenApi\Undefined;

/**
 * Shorthand for a json response.
 *
 * Example:
 * ```php
 * #[OA\JsonContent(
 *     ref: '#/components/schemas/user'
 * )]
 * ```
 * vs.
 * ```php
 * #[OA\MediaType(
 *     mediaType: 'application/json',
 *     schema: new OA\Schema(
 *         ref: '#/components/schemas/user'
 *     )
 * )
 * ```
 *
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class JsonContent extends OA\JsonContent
{
    /**
     * @param list<Encoding>                                               $encoding
     * @param string|class-string|object|null                              $ref
     * @param list<string>                                                 $required
     * @param list<Property>                                               $properties
     * @param string|non-empty-array<string>|null                          $type
     * @param array<Examples>                                              $examples
     * @param array<Schema|OA\Schema>                                      $allOf
     * @param array<Schema|OA\Schema>                                      $anyOf
     * @param array<Schema|OA\Schema>                                      $oneOf
     * @param list<string|int|float|bool|\UnitEnum|null>|class-string|null $enum
     * @param array<string,mixed>|null                                     $x
     * @param list<Attachable>|null                                        $attachables
     */
    public function __construct(
        ?array $encoding = null,

        // Schema
        string|object|null $ref = null,
        ?string $schema = null,
        ?string $title = null,
        ?string $description = Undefined::UNDEFINED,
        ?int $maxProperties = null,
        ?int $minProperties = null,
        ?array $required = null,
        ?array $properties = null,
        string|array|null $type = null,
        ?string $format = null,
        ?Items $items = null,
        ?string $collectionFormat = null,
        ?string $pattern = null,
        ?Discriminator $discriminator = null,
        ?bool $readOnly = null,
        ?bool $writeOnly = null,
        ?Xml $xml = null,
        ?ExternalDocumentation $externalDocs = null,
        mixed $example = Undefined::UNDEFINED,
        ?array $examples = null,
        ?bool $nullable = null,
        ?bool $deprecated = null,
        ?array $allOf = null,
        ?array $anyOf = null,
        ?array $oneOf = null,
        ?string $contentEncoding = null,
        ?string $contentMediaType = null,

        // JSON Schema
        mixed $default = Undefined::UNDEFINED,
        int|float|null $maximum = null,
        bool|int|float|null $exclusiveMaximum = null,
        int|float|null $minimum = null,
        bool|int|float|null $exclusiveMinimum = null,
        int|null $maxLength = null,
        int|null $minLength = null,
        int|null $maxItems = null,
        int|null $minItems = null,
        bool|null $uniqueItems = null,
        array|string|null $enum = null,
        mixed $not = Undefined::UNDEFINED,
        bool|AdditionalProperties|null $additionalProperties = null,
        array|null $additionalItems = null,
        array|null $contains = null,
        array|null $patternProperties = null,
        array|null $unevaluatedProperties = null,
        mixed $dependencies = Undefined::UNDEFINED,
        mixed $propertyNames = Undefined::UNDEFINED,
        mixed $const = Undefined::UNDEFINED,

        // abstract annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct([
            // Schema
            'ref' => $ref ?? Undefined::UNDEFINED,
            'schema' => $schema ?? Undefined::UNDEFINED,
            'title' => $title ?? Undefined::UNDEFINED,
            'description' => $description,
            'maxProperties' => $maxProperties ?? Undefined::UNDEFINED,
            'minProperties' => $minProperties ?? Undefined::UNDEFINED,
            'required' => $required ?? Undefined::UNDEFINED,
            'properties' => $properties ?? Undefined::UNDEFINED,
            'type' => $type ?? Undefined::UNDEFINED,
            'format' => $format ?? Undefined::UNDEFINED,
            'collectionFormat' => $collectionFormat ?? Undefined::UNDEFINED,
            'pattern' => $pattern ?? Undefined::UNDEFINED,
            'readOnly' => $readOnly ?? Undefined::UNDEFINED,
            'writeOnly' => $writeOnly ?? Undefined::UNDEFINED,
            'xml' => $xml ?? Undefined::UNDEFINED,
            'example' => $example,
            'nullable' => $nullable ?? Undefined::UNDEFINED,
            'deprecated' => $deprecated ?? Undefined::UNDEFINED,
            'allOf' => $allOf ?? Undefined::UNDEFINED,
            'anyOf' => $anyOf ?? Undefined::UNDEFINED,
            'oneOf' => $oneOf ?? Undefined::UNDEFINED,
            'contentEncoding' => $contentEncoding ?? Undefined::UNDEFINED,
            'contentMediaType' => $contentMediaType ?? Undefined::UNDEFINED,

            // JSON Schema
            'default' => $default,
            'maximum' => $maximum ?? Undefined::UNDEFINED,
            'exclusiveMaximum' => $exclusiveMaximum ?? Undefined::UNDEFINED,
            'minimum' => $minimum ?? Undefined::UNDEFINED,
            'exclusiveMinimum' => $exclusiveMinimum ?? Undefined::UNDEFINED,
            'maxLength' => $maxLength ?? Undefined::UNDEFINED,
            'minLength' => $minLength ?? Undefined::UNDEFINED,
            'maxItems' => $maxItems ?? Undefined::UNDEFINED,
            'minItems' => $minItems ?? Undefined::UNDEFINED,
            'uniqueItems' => $uniqueItems ?? Undefined::UNDEFINED,
            'enum' => $enum ?? Undefined::UNDEFINED,
            'not' => $not,
            'additionalProperties' => $additionalProperties ?? Undefined::UNDEFINED,
            'additionalItems' => $additionalItems ?? Undefined::UNDEFINED,
            'contains' => $contains ?? Undefined::UNDEFINED,
            'patternProperties' => $patternProperties ?? Undefined::UNDEFINED,
            'unevaluatedProperties' => $unevaluatedProperties ?? Undefined::UNDEFINED,
            'dependencies' => $dependencies,
            'propertyNames' => $propertyNames,
            'const' => $const,

            // abstract annotation
            'x' => $x ?? Undefined::UNDEFINED,
            'attachables' => $attachables ?? Undefined::UNDEFINED,
            'value' => $this->combine($items, $discriminator, $externalDocs, $examples, $encoding),
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

/**
 * Shorthand for a json response.
 *
 * Example:
 * ```php
 * #[OA\JsonContent(
 *     ref: '#/components/schemas/user'
 * )]
 * ```
 * vs.
 * ```php
 * #[OA\MediaType(
 *     mediaType: 'application/json',
 *     schema: new OA\Schema(
 *         ref: '#/components/schemas/user'
 *     )
 * )
 * ```
 *
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class JsonContent extends OA\JsonContent
{
    /**
     * @param array<Examples>                                               $examples
     * @param string|class-string|object|null                               $ref
     * @param string[]                                                      $required
     * @param Property[]                                                    $properties
     * @param string|non-empty-array<string>|null                           $type
     * @param int|float                                                     $maximum
     * @param int|float                                                     $minimum
     * @param array<string|int|float|bool|\UnitEnum|null>|class-string|null $enum
     * @param array<Schema|OA\Schema>                                       $allOf
     * @param array<Schema|OA\Schema>                                       $anyOf
     * @param array<Schema|OA\Schema>                                       $oneOf
     * @param Encoding[]                                                    $encoding
     * @param array<string,mixed>|null                                      $x
     * @param Attachable[]|null                                             $attachables
     */
    public function __construct(
        ?array $examples = null,
        // schema
        string|object|null $ref = null,
        ?string $schema = null,
        ?string $title = null,
        ?string $description = Generator::UNDEFINED,
        ?int $maxProperties = null,
        ?int $minProperties = null,
        ?array $required = null,
        ?array $properties = null,
        string|array|null $type = null,
        ?string $format = null,
        ?Items $items = null,
        ?string $collectionFormat = null,
        mixed $default = Generator::UNDEFINED,
        $maximum = null,
        bool|int|float|null $exclusiveMaximum = null,
        $minimum = null,
        bool|int|float|null $exclusiveMinimum = null,
        ?int $maxLength = null,
        ?int $minLength = null,
        ?int $maxItems = null,
        ?int $minItems = null,
        ?bool $uniqueItems = null,
        ?string $pattern = null,
        array|string|null $enum = null,
        ?Discriminator $discriminator = null,
        ?bool $readOnly = null,
        ?bool $writeOnly = null,
        ?Xml $xml = null,
        ?ExternalDocumentation $externalDocs = null,
        mixed $example = Generator::UNDEFINED,
        ?bool $nullable = null,
        ?bool $deprecated = null,
        ?array $allOf = null,
        ?array $anyOf = null,
        ?array $oneOf = null,
        AdditionalProperties|bool|null $additionalProperties = null,
        ?array $patternProperties = null,
        ?array $unevaluatedProperties = null,
        ?array $encoding = null,
        ?string $contentEncoding = null,
        ?string $contentMediaType = null,
        // annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct([
            'examples' => $examples ?? Generator::UNDEFINED,
            // schema
            'ref' => $ref ?? Generator::UNDEFINED,
            'schema' => $schema ?? Generator::UNDEFINED,
            'title' => $title ?? Generator::UNDEFINED,
            'description' => $description,
            'maxProperties' => $maxProperties ?? Generator::UNDEFINED,
            'minProperties' => $minProperties ?? Generator::UNDEFINED,
            'required' => $required ?? Generator::UNDEFINED,
            'properties' => $properties ?? Generator::UNDEFINED,
            'type' => $type ?? Generator::UNDEFINED,
            'format' => $format ?? Generator::UNDEFINED,
            'collectionFormat' => $collectionFormat ?? Generator::UNDEFINED,
            'default' => $default,
            'maximum' => $maximum ?? Generator::UNDEFINED,
            'exclusiveMaximum' => $exclusiveMaximum ?? Generator::UNDEFINED,
            'minimum' => $minimum ?? Generator::UNDEFINED,
            'exclusiveMinimum' => $exclusiveMinimum ?? Generator::UNDEFINED,
            'maxLength' => $maxLength ?? Generator::UNDEFINED,
            'minLength' => $minLength ?? Generator::UNDEFINED,
            'maxItems' => $maxItems ?? Generator::UNDEFINED,
            'minItems' => $minItems ?? Generator::UNDEFINED,
            'uniqueItems' => $uniqueItems ?? Generator::UNDEFINED,
            'pattern' => $pattern ?? Generator::UNDEFINED,
            'enum' => $enum ?? Generator::UNDEFINED,
            'readOnly' => $readOnly ?? Generator::UNDEFINED,
            'writeOnly' => $writeOnly ?? Generator::UNDEFINED,
            'xml' => $xml ?? Generator::UNDEFINED,
            'example' => $example,
            'nullable' => $nullable ?? Generator::UNDEFINED,
            'deprecated' => $deprecated ?? Generator::UNDEFINED,
            'allOf' => $allOf ?? Generator::UNDEFINED,
            'anyOf' => $anyOf ?? Generator::UNDEFINED,
            'oneOf' => $oneOf ?? Generator::UNDEFINED,
            'additionalProperties' => $additionalProperties ?? Generator::UNDEFINED,
            'patternProperties' => $patternProperties ?? Generator::UNDEFINED,
            'unevaluatedProperties' => $unevaluatedProperties ?? Generator::UNDEFINED,
            'encoding' => $encoding ?? Generator::UNDEFINED,
            'contentEncoding' => $contentEncoding ?? Generator::UNDEFINED,
            'contentMediaType' => $contentMediaType ?? Generator::UNDEFINED,
            // annotation
            'x' => $x ?? Generator::UNDEFINED,
            'attachables' => $attachables ?? Generator::UNDEFINED,
            'value' => $this->combine($items, $discriminator, $externalDocs),
        ]);
    }
}
>>>>>>> Current commit: Начало обновления до api v2
