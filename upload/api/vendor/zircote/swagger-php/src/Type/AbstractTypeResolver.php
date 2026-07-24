<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Type;

use OpenApi\Analysis;
use OpenApi\Annotations\AbstractAnnotation;
use OpenApi\Annotations as OA;
use OpenApi\TypeResolverInterface;
use OpenApi\Undefined;
use OpenApi\Utils\TypeMapper;

abstract class AbstractTypeResolver implements TypeResolverInterface
{
    protected TypeMapper $typeMapper;

    public function __construct()
    {
        $this->typeMapper = new TypeMapper();
    }

    /**
     * @param string|array $type
     */
    public function mapNativeType(OA\Schema $schema, $type): bool
    {
        if (is_array($type)) {
            $schema->type = $this->typeMapper->toSpecTypes(
                array_map(static fn ($t): string => strtolower((string) $t), $type)
            );

            return true;
        }

        $result = $this->typeMapper->map($type);
        if (null === $result) {
            return false;
        }

        if ('mixed' === $result['type']) {
            return true;
        }

        if (null !== $result['format'] && Undefined::isDefault($schema->format)) {
            $schema->format = $result['format'];
        }

        $schema->type = $result['type'];

        return true;
    }

    public function native2spec(string $type): string
    {
        return $this->typeMapper->toSpecType($type);
    }

    public function augmentSchemaType(Analysis $analysis, OA\Schema $schema, string $sourceClass = OA\Schema::class): void
    {
        $context = $schema->_context;

        if (null === $context->reflector || $context->nested) {
            return;
        }

        /* @phpstan-ignore argument.type */
        $this->doAugment($analysis, $schema, $context->reflector, $sourceClass);

        $this->mapNativeType($schema, $schema->type);
    }

    protected function type2ref(OA\Schema $schema, Analysis $analysis, string $sourceClass = OA\Schema::class): void
    {
        if (!Undefined::isDefault($schema->type) && !is_array($schema->type)) {
            if (($typeSchema = $analysis->getAnnotationForSource($schema->type, $sourceClass)) instanceof AbstractAnnotation) {
                $schema->type = Undefined::UNDEFINED;
                $schema->ref = OA\Components::ref($typeSchema);
            }
        }
    }

    /**
     * @param \ReflectionParameter|\ReflectionProperty|\ReflectionMethod $reflector
     */
    abstract protected function doAugment(Analysis $analysis, OA\Schema $schema, \Reflector $reflector, string $sourceClass = OA\Schema::class): void;
}
|||||||
=======
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Type;

use OpenApi\Analysis;
use OpenApi\Annotations as OA;
use OpenApi\Context;
use OpenApi\Generator;
use OpenApi\TypeResolverInterface;

abstract class AbstractTypeResolver implements TypeResolverInterface
{
    protected function type2ref(OA\Schema $schema, Analysis $analysis, string $sourceClass = OA\Schema::class): void
    {
        if (!Generator::isDefault($schema->type)) {
            if ($typeSchema = $analysis->getAnnotationForSource($schema->type, $sourceClass)) {
                $schema->type = Generator::UNDEFINED;
                $schema->ref = OA\Components::ref($typeSchema);
            }
        }
    }

    protected function augmentItems(OA\Schema $schema, Analysis $analysis): void
    {
        if (!Generator::isDefault($schema->type)) {
            if (Generator::isDefault($schema->items)) {
                $schema->items = new OA\Items([
                    'type' => $schema->type,
                    '_context' => new Context(['generated' => true], $schema->_context),
                ]);

                $this->type2ref($schema->items, $analysis);

                $analysis->addAnnotation($schema->items, $schema->items->_context);

                if (!Generator::isDefault($schema->ref)) {
                    $schema->items->ref = $schema->ref;
                    $schema->ref = Generator::UNDEFINED;
                }
            } elseif (Generator::isDefault($schema->items->type, $schema->items->oneOf, $schema->items->allOf, $schema->items->anyOf)) {
                $schema->items->type = $schema->type;

                $this->type2ref($schema->items, $analysis);
            }
        }

        if (!Generator::isDefault($schema->items)) {
            $this->mapNativeType($schema->items, $schema->items->type);
            $schema->type = 'array';
        }
    }

    /**
     * @param string|array $type
     */
    public function mapNativeType(OA\Schema $schema, $type): bool
    {
        if (is_array($type)) {
            $mapped = [];
            foreach ($type as $t) {
                $mapped[] = $this->native2spec(strtolower($t));
            }

            $schema->type = $mapped;

            return true;
        }

        $type = strtolower($type);
        if (!array_key_exists($type, TypeResolverInterface::NATIVE_TYPE_MAP)) {
            return false;
        }

        $type = TypeResolverInterface::NATIVE_TYPE_MAP[$type];
        if (is_array($type)) {
            if (Generator::isDefault($schema->format)) {
                $schema->format = $type[1];
            }
            $type = $type[0];
        }

        $schema->type = $type;

        return true;
    }

    public function native2spec(string $type): string
    {
        $mapped = array_key_exists($type, TypeResolverInterface::NATIVE_TYPE_MAP)
            ? TypeResolverInterface::NATIVE_TYPE_MAP[$type]
            : $type;

        return is_array($mapped) ? $mapped[0] : $mapped;
    }

    public function augmentSchemaType(Analysis $analysis, OA\Schema $schema, string $sourceClass = OA\Schema::class): void
    {
        $context = $schema->_context;

        if (null === $context->reflector || $context->nested) {
            return;
        }

        /* @phpstan-ignore argument.type */
        $this->doAugment($analysis, $schema, $context->reflector, $sourceClass);

        $this->mapNativeType($schema, $schema->type);
    }

    /**
     * @param \ReflectionParameter|\ReflectionProperty|\ReflectionMethod $reflector
     */
    abstract protected function doAugment(Analysis $analysis, OA\Schema $schema, \Reflector $reflector, string $sourceClass = OA\Schema::class): void;
}
>>>>>>> Current commit: Начало обновления до api v2
