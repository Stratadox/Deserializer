<?php
declare(strict_types=1);

namespace Stratadox\Deserializer;

use Stratadox\Hydrator\ImmutableCollectionHydrator;
use Stratadox\Hydrator\MutableCollectionHydrator;
use function is_iterable;
use function is_string;
use Stratadox\Hydrator\HydrationFailure;
use Stratadox\Hydrator\Hydrator;
use Stratadox\Instantiator\InstantiationFailure;
use Stratadox\Instantiator\Instantiator;
use Stratadox\Instantiator\ObjectInstantiator;

/**
 * Deserializes the input array into collection objects.
 *
 * @author Stratadox
 */
final class CollectionDeserializer implements Deserializer
{
    private function __construct(
        private Instantiator $make,
        private Hydrator $hydrator
    ) {}

    /**
     * Makes a new deserializer for an immutable collection class.
     *
     * @param string $class The fully qualified collection class name.
     * @return Deserializer The collection deserializer.
     * @throws InstantiationFailure
     */
    public static function forImmutable(string $class): Deserializer
    {
        return new CollectionDeserializer(
            ObjectInstantiator::forThe($class),
            ImmutableCollectionHydrator::default()
        );
    }

    /**
     * Makes a new deserializer for a mutable collection class.
     *
     * @param string $class The fully qualified collection class name.
     * @return Deserializer The collection deserializer.
     * @throws InstantiationFailure
     */
    public static function forMutable(string $class): Deserializer
    {
        return new CollectionDeserializer(
            ObjectInstantiator::forThe($class),
            MutableCollectionHydrator::default()
        );
    }

    /**
     * Makes a new deserializer for the collection class, using custom
     * instantiator and hydrator.
     *
     * @param Instantiator $instantiator The object that produces instances.
     * @param Hydrator     $hydrator     The object that writes properties.
     * @return Deserializer              The collection deserializer.
     */
    public static function using(
        Instantiator $instantiator,
        Hydrator $hydrator
    ): Deserializer {
        return new CollectionDeserializer($instantiator, $hydrator);
    }

    /** @inheritdoc */
    public function from(array $input): iterable
    {
        $collection = $this->make->instance();
        $this->mustBeValid($collection, $input);
        try {
            $this->hydrator->writeTo($collection, $input);
        } catch (HydrationFailure $exception) {
            throw FailedToDeserializeTheCollection::encountered($exception);
        }
        if (is_iterable($collection)) {
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

    /** @throws DeserializationFailure */
    private function mustBeValid(object $collection, array $input): void
    {
        foreach ($input as $key => $value) {
            if (is_string($key)) {
                throw IllegalInputKey::illegal($collection, $key, $input);
            }
        }
    }
}
