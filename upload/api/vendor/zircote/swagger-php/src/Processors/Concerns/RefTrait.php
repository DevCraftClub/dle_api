<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Processors\Concerns;

use OpenApi\Context;

trait RefTrait
{
    protected function toRefKey(Context $context, ?string $name): string
    {
        $fqn = strtolower($context->fullyQualifiedName($name) ?? '');

        return ltrim($fqn, '\\');
    }

    protected function isRef(?string $ref): bool
    {
        return $ref && str_starts_with($ref, '#/');
    }
}
|||||||
=======
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Processors\Concerns;

use OpenApi\Context;

trait RefTrait
{
    protected function toRefKey(Context $context, ?string $name): string
    {
        $fqn = strtolower($context->fullyQualifiedName($name));

        return ltrim($fqn, '\\');
    }

    protected function isRef(?string $ref): bool
    {
        return $ref && 0 === strpos($ref, '#/');
    }
}
>>>>>>> Current commit: Начало обновления до api v2
