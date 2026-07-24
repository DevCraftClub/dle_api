<<<<<<< New base: Update README.md
<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Type;

use OpenApi\Analysis;
use OpenApi\Annotations as OA;
use OpenApi\Context;
use OpenApi\Undefined;

class TypeInfoTypeResolver extends AbstractTypeResolver
{
    protected TypeResolver $resolver;

    public function __construct()
    {
        parent::__construct();
        $this->resolver = new TypeResolver();
    }

    /**
     * @inheritdoc
     */
    protected function doAugment(Analysis $analysis, OA\Schema $schema, \Reflector $reflector, string $sourceClass = OA\Schema::class): void
    {
        $schemaType = $this->resolver->resolve($reflector);

        if (!$schemaType instanceof SchemaType) {
            $this->handlePostAugment($schema);

            return;
        }

        if (Undefined::isDefault($schema->nullable) && $schemaType->nullable === true) {
            $schema->nullable = true;
        }

        if (Undefined::isDefault($schema->type, $schema->oneOf, $schema->allOf, $schema->anyOf)) {
            $this->applyToAnnotation($schema, $schemaType, $analysis, $sourceClass);
        }

        $this->type2ref($schema, $analysis, $sourceClass);

        $this->handlePostAugment($schema);
    }

    protected function handlePostAugment(OA\Schema $schema): void
    {
        if ($schema->items instanceof OA\Items) {
            $schema->type = 'array';
        }

        if (!Undefined::isDefault($schema->const) && Undefined::isDefault($schema->type)) {
            if (!$this->mapNativeType($schema, gettype($schema->const))) {
                $schema->type = Undefined::UNDEFINED;
            }
        }

        if (!Undefined::isDefault($schema->type) && !$this->mapNativeType($schema, $schema->type)) {
            $schema->type = Undefined::UNDEFINED;
        }
    }

    protected function applyToAnnotation(OA\Schema $schema, SchemaType $schemaType, Analysis $analysis, string $sourceClass = OA\Schema::class): void
    {
        if ($schemaType->type !== null) {
            $schema->type = $schemaType->type;
        }

        if ($schemaType->format !== null && Undefined::isDefault($schema->format)) {
            $schema->format = $schemaType->format;
        }

        if ($schemaType->minimum !== null) {
            $schema->minimum = $schemaType->minimum;
        }

        if ($schemaType->maximum !== null) {
            $schema->maximum = $schemaType->maximum;
        }

        if ($schemaType->not !== null) {
            $schema->not = $schemaType->not;
        }

        if ($schemaType->items instanceof SchemaType) {
            $schema->type = 'array';
            if (Undefined::isDefault($schema->items)) {
                $schema->items = new OA\Items(['_context' => new Context(['generated' => true], $schema->_context)]);
                $this->applyToAnnotation($schema->items, $schemaType->items, $analysis, $sourceClass);
                $this->type2ref($schema->items, $analysis, $sourceClass);
                $analysis->addAnnotation($schema->items, $schema->items->_context);
            } elseif (Undefined::isDefault($schema->items->type, $schema->items->oneOf, $schema->items->allOf, $schema->items->anyOf)) {
                $this->applyToAnnotation($schema->items, $schemaType->items, $analysis, $sourceClass);
                $this->type2ref($schema->items, $analysis, $sourceClass);
            }
            $this->mapNativeType($schema->items, $schema->items->type);
        }

        if ($schemaType->additionalProperties instanceof SchemaType) {
            $schema->type = 'object';
            if (Undefined::isDefault($schema->additionalProperties)) {
                $schema->additionalProperties = new OA\AdditionalProperties(['_context' => new Context(['generated' => true], $schema->_context)]);
                $this->applyToAnnotation($schema->additionalProperties, $schemaType->additionalProperties, $analysis, $sourceClass);
                $this->type2ref($schema->additionalProperties, $analysis, $sourceClass);
                $analysis->addAnnotation($schema->additionalProperties, $schema->additionalProperties->_context);
            } elseif (Undefined::isDefault($schema->additionalProperties->type, $schema->additionalProperties->oneOf, $schema->additionalProperties->allOf, $schema->additionalProperties->anyOf)) {
                $this->applyToAnnotation($schema->additionalProperties, $schemaType->additionalProperties, $analysis, $sourceClass);
                $this->type2ref($schema->additionalProperties, $analysis, $sourceClass);
            }
            $this->mapNativeType($schema->additionalProperties, $schema->additionalProperties->type);
        } elseif ($schemaType->additionalProperties === true) {
            if (Undefined::isDefault($schema->additionalProperties)) {
                $schema->additionalProperties = new OA\AdditionalProperties(['_context' => new Context(['generated' => true], $schema->_context)]);
                $analysis->addAnnotation($schema->additionalProperties, $schema->additionalProperties->_context);
            }
        }

        if ($schemaType->oneOf !== null) {
            if ($schema->items instanceof OA\Items) {
                return;
            }
            $schema->type = Undefined::UNDEFINED;
            $schema->oneOf = [];
            foreach ($schemaType->oneOf as $childType) {
                $childSchema = new OA\Schema(['_context' => new Context(['generated' => true], $schema->_context)]);
                $this->applyToAnnotation($childSchema, $childType, $analysis, $sourceClass);
                $this->type2ref($childSchema, $analysis, $sourceClass);
                $analysis->addAnnotation($childSchema, $childSchema->_context);
                $schema->oneOf[] = $childSchema;
            }
        }

        if ($schemaType->allOf !== null) {
            $schema->type = Undefined::UNDEFINED;
            $schema->allOf = [];
            foreach ($schemaType->allOf as $childType) {
                $childSchema = new OA\Schema(['_context' => new Context(['generated' => true], $schema->_context)]);
                $this->applyToAnnotation($childSchema, $childType, $analysis, $sourceClass);
                $this->type2ref($childSchema, $analysis, $sourceClass);
                $analysis->addAnnotation($childSchema, $childSchema->_context);
                $schema->allOf[] = $childSchema;
            }
        }

        if ($schemaType->properties !== null) {
            $schema->type = 'object';
            $properties = [];
            foreach ($schemaType->properties as $name => $propType) {
                $property = new OA\Property([
                    'property' => $name,
                    '_context' => new Context(['generated' => true], $schema->_context),
                ]);
                $this->applyToAnnotation($property, $propType, $analysis, $sourceClass);
                $this->type2ref($property, $analysis, $sourceClass);
                $this->mapNativeType($property, $property->type);
                $analysis->addAnnotation($property, $property->_context);
                $properties[] = $property;
            }
            $schema->properties = $properties;

            if ($schemaType->required !== null && [] !== $schemaType->required) {
                $schema->required = $schemaType->required;
            }
        }
    }
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
use OpenApi\Generator;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use Radebatz\TypeInfoExtras\Type\ExplicitType;
use Radebatz\TypeInfoExtras\Type\IntRangeType;
use Radebatz\TypeInfoExtras\TypeResolver\StringTypeResolver;
use Symfony\Component\TypeInfo\Exception\UnsupportedException;
use Symfony\Component\TypeInfo\Type;
use Symfony\Component\TypeInfo\Type\BuiltinType;
use Symfony\Component\TypeInfo\Type\CollectionType;
use Symfony\Component\TypeInfo\Type\CompositeTypeInterface;
use Symfony\Component\TypeInfo\Type\NullableType;
use Symfony\Component\TypeInfo\Type\ObjectType;
use Symfony\Component\TypeInfo\TypeContext\TypeContextFactory;
use Symfony\Component\TypeInfo\TypeResolver\ReflectionTypeResolver;

class TypeInfoTypeResolver extends AbstractTypeResolver
{
    /** @inheritdoc */
    protected function doAugment(Analysis $analysis, OA\Schema $schema, \Reflector $reflector, string $sourceClass = OA\Schema::class): void
    {
        $docblockType = $this->getDocblockType($reflector);
        $reflectionType = $this->getReflectionType($reflector);

        // we only consider nullable hints if the type is explicitly set
        if (Generator::isDefault($schema->nullable)
            && (($docblockType && $docblockType->isNullable())
                || ($reflectionType && $reflectionType->isNullable()))
        ) {
            $schema->nullable = true;
        }

        $docblockType = $docblockType instanceof NullableType ? $docblockType->getWrappedType() : $docblockType;
        $reflectionType = $reflectionType instanceof NullableType ? $reflectionType->getWrappedType() : $reflectionType;

        if (Generator::isDefault($schema->type, $schema->oneOf, $schema->allOf, $schema->anyOf) && ($docblockType || $reflectionType)) {
            $type = $docblockType ?? $reflectionType;

            $isNonZeroInt = false;
            if ($type instanceof CompositeTypeInterface && 2 === count($type->getTypes())) {
                $types = $type->getTypes();
                $isNonZeroInt = $types[0] instanceof IntRangeType && $types[1] instanceof IntRangeType;
            }

            if (!$type instanceof CompositeTypeInterface || $isNonZeroInt) {
                if ($isNonZeroInt) {
                    $schema->type = 'int';
                    $schema->not = $schema->_context->isVersion('3.0.x')
                        ? ['enum' => [0]]
                        : ['const' => 0];
                } elseif ($type instanceof BuiltinType || $type instanceof ObjectType) {
                    $schema->type = (string) $type;
                } elseif ($type instanceof IntRangeType) {
                    $schema->type = $type->getTypeIdentifier()->value;

                    $schema->minimum = $type->getFrom();
                    $schema->maximum = $type->getTo();

                } elseif ($type instanceof ExplicitType) {
                    $schema->type = $type->getTypeIdentifier()->value;
                } elseif ($type instanceof CollectionType) {
                    $schema->type = (string) $type->getCollectionValueType();
                }
            }
        }

        if ($docblockType instanceof CollectionType || $reflectionType instanceof CollectionType) {
            $this->augmentItems($schema, $analysis);
        }

        $this->type2ref($schema, $analysis, $sourceClass);

        if ($schema->items instanceof OA\Items) {
            $schema->type = 'array';
        }

        if (!Generator::isDefault($schema->const) && Generator::isDefault($schema->type)) {
            if (!$this->mapNativeType($schema, gettype($schema->const))) {
                $schema->type = Generator::UNDEFINED;
            }
        }

        // final sanity check
        if (!Generator::isDefault($schema->type) && !$this->mapNativeType($schema, $schema->type)) {
            $schema->type = Generator::UNDEFINED;
        }
    }

    /**
     * @param \ReflectionParameter|\ReflectionProperty|\ReflectionMethod $reflector
     */
    protected function getReflectionType(\Reflector $reflector): ?Type
    {
        $subject = $reflector instanceof \ReflectionClass
            ? $reflector->getName()
            : (
                $reflector instanceof \ReflectionMethod
                ? $reflector->getReturnType()
                : (method_exists($reflector, 'getType') ? $reflector->getType() : null)
            );
        try {
            $typeContext = (new TypeContextFactory())->createFromReflection($reflector);

            return (new ReflectionTypeResolver())->resolve($subject, $typeContext);
        } catch (UnsupportedException $unsupportedException) {
            // ignore
        }

        return null;
    }

    /**
     * @param \ReflectionParameter|\ReflectionProperty|\ReflectionMethod $reflector
     */
    public function getDocblockType(\Reflector $reflector): ?Type
    {
        switch (true) {
            case $reflector instanceof \ReflectionProperty:
                $docComment = (method_exists($reflector, 'isPromoted') && $reflector->isPromoted())
                && $reflector->getDeclaringClass() && $reflector->getDeclaringClass()->getConstructor()
                    ? $reflector->getDeclaringClass()->getConstructor()->getDocComment()
                    : $reflector->getDocComment();
                break;
            case $reflector instanceof \ReflectionParameter:
                $docComment = $reflector->getDeclaringFunction()->getDocComment();
                break;
            case $reflector instanceof \ReflectionFunctionAbstract:
                $docComment = $reflector->getDocComment();
                break;
            default:
                $docComment = null;
        }

        if (!$docComment) {
            return null;
        }

        $typeContext = (new TypeContextFactory())->createFromReflection($reflector);

        switch (true) {
            case $reflector instanceof \ReflectionProperty:
                $tagName = (method_exists($reflector, 'isPromoted') && $reflector->isPromoted())
                    ? '@param'
                    : '@var';
                break;
            case $reflector instanceof \ReflectionParameter:
                $tagName = '@param';
                break;
            case $reflector instanceof \ReflectionFunctionAbstract:
                $tagName = '@return';
                break;
            default:
                $tagName = null;
        }

        $lexer = new Lexer(new ParserConfig([]));
        $phpDocParser = new PhpDocParser(
            $config = new ParserConfig([]),
            new TypeParser($config, $constExprParser = new ConstExprParser($config)),
            $constExprParser,
        );

        $tokens = new TokenIterator($lexer->tokenize($docComment));
        $docNode = $phpDocParser->parse($tokens);

        foreach ($docNode->getTagsByName($tagName) as $tag) {
            $tagValue = $tag->value;

            if (
                $tagValue instanceof VarTagValueNode
                || ($tagValue instanceof ParamTagValueNode && $tagName && '$' . $reflector->getName() === $tagValue->parameterName)
                || $tagValue instanceof ReturnTagValueNode
            ) {
                try {
                    return (new StringTypeResolver())->resolve((string) $tagValue, $typeContext);
                } catch (UnsupportedException $e) {
                    // ignore
                }
            }
        }

        return null;
    }
}
>>>>>>> Current commit: Начало обновления до api v2
