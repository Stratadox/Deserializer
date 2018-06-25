<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use function class_parents as listOfParentsForThe;
use Stratadox\Hydrator\CannotHydrate;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Hydrator\ReflectiveHydrator;
use Stratadox\Instantiator\CannotInstantiateThis;
use Stratadox\Instantiator\Instantiator;
use Stratadox\Instantiator\ProvidesInstances;

final class ObjectDeserializer implements DeserializesObjects
{
    private $make;
    private $hydrator;

    private function __construct(ProvidesInstances $instance, Hydrates $hydrate)
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
     * @param string $class            The fully qualified collection class name.
     * @return DeserializesObjects     The object deserializer.
     * @throws CannotInstantiateThis   When the class cannot be instantiated.
     */
    public static function forThe(string $class): DeserializesObjects
    {
        return new ObjectDeserializer(
            Instantiator::forThe($class),
            empty(listOfParentsForThe($class))
                ? ObjectHydrator::default()
                : ReflectiveHydrator::default()
        );
    }

    /**
     * Makes a new deserializer for the class, using custom instantiator and
     * hydrator.
     *
     * @param ProvidesInstances $instantiator The object that produces instances.
     * @param Hydrates          $hydrator     The object that writes properties.
     * @return DeserializesObjects            The object deserializer.
     */
    public static function using(
        ProvidesInstances $instantiator,
        Hydrates $hydrator
    ): DeserializesObjects {
        return new ObjectDeserializer($instantiator, $hydrator);
    }

    /** @inheritdoc */
    public function from(array $input): object
    {
        $object = $this->make->instance();
        try {
            $this->hydrator->writeTo($object, $input);
        } catch (CannotHydrate $exception) {
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
