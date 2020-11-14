<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use Stratadox\Hydrator\HydrationFailure;
use Stratadox\Hydrator\Hydrator;
use Stratadox\Instantiator\InstantiationFailure;
use Stratadox\Instantiator\ObjectInstantiator;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Hydrator\ReflectiveHydrator;
use Stratadox\Instantiator\Instantiator;
use function class_parents;

/**
 * Deserializes the input array into objects.
 *
 * @author Stratadox
 */
final class ObjectDeserializer implements Deserializer
{
    /** @var Instantiator */
    private $make;
    /** @var Hydrator */
    private $hydrator;

    private function __construct(Instantiator $instance, Hydrator $hydrate)
    {
        $this->make = $instance;
        $this->hydrator = $hydrate;
    }

    /**
     * Makes a new deserializer for the class.
     *
     * Produces a default instantiator and hydrator for the class. If the class
     * uses inheritance, a reflective hydrator is used by default. This may not
     * always be necessary. (ie. when not inheriting private properties) In such
     * cases, @see ObjectDeserializer::using can be used instead.
     *
     * @param string $class         The fully qualified collection class name.
     * @return Deserializer         The object deserializer.
     * @throws InstantiationFailure When the class cannot be instantiated.
     */
    public static function forThe(string $class): Deserializer
    {
        return new ObjectDeserializer(
            ObjectInstantiator::forThe($class),
            empty(class_parents($class))
                ? ObjectHydrator::default()
                : ReflectiveHydrator::default()
        );
    }

    /**
     * Makes a new deserializer for the class, using custom instantiator and
     * hydrator.
     *
     * @param Instantiator $instantiator The object that produces instances.
     * @param Hydrator     $hydrator     The object that writes properties.
     * @return Deserializer              The object deserializer.
     */
    public static function using(
        Instantiator $instantiator,
        Hydrator $hydrator
    ): Deserializer {
        return new ObjectDeserializer($instantiator, $hydrator);
    }

    /** @inheritdoc */
    public function from(array $input): object
    {
        $object = $this->make->instance();
        try {
            $this->hydrator->writeTo($object, $input);
        } catch (HydrationFailure $exception) {
            throw FailedToDeserializeTheObject::encountered($exception);
        }
        return $object;
    }

    /** @inheritdoc */
    public function typeFor(array $input): string
    {
        return $this->make->class();
    }
}
