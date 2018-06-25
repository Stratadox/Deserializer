<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use function is_iterable as itIsAnIterable;
use function is_string as itIsNotANumeric;
use Stratadox\Hydrator\CannotHydrate;
use Stratadox\Hydrator\CollectionHydrator;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Instantiator\CannotInstantiateThis;
use Stratadox\Instantiator\Instantiator;
use Stratadox\Instantiator\ProvidesInstances;

/**
 * Collection object deserializer.
 *
 * @author Stratadox
 */
final class CollectionDeserializer implements DeserializesCollections
{
    private $make;
    private $hydrator;

    private function __construct(ProvidesInstances $instance, Hydrates $hydrate)
    {
        $this->make = $instance;
        $this->hydrator = $hydrate;
    }

    /**
     * Makes a new deserializer for the collection class.
     *
     * @param string $class            The fully qualified collection class name.
     * @return DeserializesCollections The collection deserializer.
     * @throws CannotInstantiateThis   When the class cannot be instantiated.
     */
    public static function forThe(string $class): DeserializesCollections
    {
        return new CollectionDeserializer(
            Instantiator::forThe($class),
            CollectionHydrator::default()
        );
    }

    /**
     * Makes a new deserializer for the collection class, using custom
     * instantiator and hydrator.
     *
     * @param ProvidesInstances $instantiator The object that produces instances.
     * @param Hydrates          $hydrator     The object that writes properties.
     * @return DeserializesCollections        The collection deserializer.
     */
    public static function using(
        ProvidesInstances $instantiator,
        Hydrates $hydrator
    ): DeserializesCollections {
        return new CollectionDeserializer($instantiator, $hydrator);
    }

    /** @inheritdoc */
    public function from(array $input): iterable
    {
        $collection = $this->make->instance();
        $this->mustBeValid($collection, $input);
        try {
            $this->hydrator->writeTo($collection, $input);
        } catch (CannotHydrate $exception) {
            throw FailedToDeserializeTheCollection::encountered($exception);
        }
        if (itIsAnIterable($collection)) {
            /** @var iterable $collection */
            return $collection;
        }
        throw NonIterableCollection::invalid($collection);
    }

    /** @inheritdoc */
    public function typeFor(array $input): string
    {
        return $this->make->class();
    }

    /** @throws CannotDeserialize */
    private function mustBeValid(object $collection, array $input): void
    {
        foreach ($input as $key => $value) {
            if (itIsNotANumeric($key)) {
                throw IllegalInputKey::illegal($collection, $key);
            }
        }
    }
}
