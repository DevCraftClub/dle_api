<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Attributes;

use OpenApi\Undefined;

trait OperationTrait
{
    /**
     * @param list<Server>             $servers
     * @param list<string>             $tags
     * @param list<Parameter>          $parameters
     * @param list<Response>           $responses
     * @param array<string,mixed>|null $x
     * @param list<Attachable>|null    $attachables
     */
    public function __construct(
        ?string $path = null,
        ?string $operationId = null,
        ?string $description = Undefined::UNDEFINED,
        ?string $summary = Undefined::UNDEFINED,
        ?array $security = null,
        ?array $servers = null,
        ?RequestBody $requestBody = null,
        ?array $tags = null,
        ?array $parameters = null,
        ?array $responses = null,
        ?array $callbacks = null,
        ?ExternalDocumentation $externalDocs = null,
        ?bool $deprecated = null,

        // abstract annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct([
                'path' => $path ?? Undefined::UNDEFINED,
                'operationId' => $operationId ?? Undefined::UNDEFINED,
                'description' => $description,
                'summary' => $summary,
                'security' => $security ?? Undefined::UNDEFINED,
                'servers' => $servers ?? Undefined::UNDEFINED,
                'tags' => $tags ?? Undefined::UNDEFINED,
                'callbacks' => $callbacks ?? Undefined::UNDEFINED,
                'deprecated' => $deprecated ?? Undefined::UNDEFINED,
                'x' => $x ?? Undefined::UNDEFINED,
                'attachables' => $attachables ?? Undefined::UNDEFINED,
                'value' => $this->combine($requestBody, $responses, $parameters, $externalDocs),
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

use OpenApi\Generator;

trait OperationTrait
{
    /**
     * @param Server[]                 $servers
     * @param string[]                 $tags
     * @param Parameter[]              $parameters
     * @param Response[]               $responses
     * @param array<string,mixed>|null $x
     * @param Attachable[]|null        $attachables
     */
    public function __construct(
        ?string $path = null,
        ?string $operationId = null,
        ?string $description = Generator::UNDEFINED,
        ?string $summary = Generator::UNDEFINED,
        ?array $security = null,
        ?array $servers = null,
        ?RequestBody $requestBody = null,
        ?array $tags = null,
        ?array $parameters = null,
        ?array $responses = null,
        ?array $callbacks = null,
        ?ExternalDocumentation $externalDocs = null,
        ?bool $deprecated = null,
        // annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct([
                'path' => $path ?? Generator::UNDEFINED,
                'operationId' => $operationId ?? Generator::UNDEFINED,
                'description' => $description,
                'summary' => $summary,
                'security' => $security ?? Generator::UNDEFINED,
                'servers' => $servers ?? Generator::UNDEFINED,
                'tags' => $tags ?? Generator::UNDEFINED,
                'callbacks' => $callbacks ?? Generator::UNDEFINED,
                'deprecated' => $deprecated ?? Generator::UNDEFINED,
                'x' => $x ?? Generator::UNDEFINED,
                'attachables' => $attachables ?? Generator::UNDEFINED,
                'value' => $this->combine($requestBody, $responses, $parameters, $externalDocs),
            ]);
    }
}
>>>>>>> Current commit: Начало обновления до api v2
