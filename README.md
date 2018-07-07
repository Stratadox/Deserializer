# Deserializer

Transforms serialized data into objects.

## Installation

Install with `composer require stratadox/deserializer`

## What is this?

An object that [`Deserializes`](https://github.com/Stratadox/DeserializerContracts)
can convert `serialized` data into objects.

The `serialized` input data is expected to have the form of an array, either 
numeric or associative.
This way, one can easily convert both the results of sql queries and decoded json,
as well as session data and other sources.

## How to use this?

To write the contents of an associative array to an object, one can use:
```php
<?php
use Stratadox\Deserializer\ObjectDeserializer;

$deserialize = ObjectDeserializer::forThe(Foo::class);
$foo = $deserialize->from([
    'bar' => 'Bar.',
    'baz' => 'BAZ!',
]);

assert($foo instanceof Foo);
assert('Bar.' === $foo->bar);
assert('BAZ!' === $foo->getBaz());
```

For writing the contents of a numeric array into a collection object, use:
```php
<?php
use Stratadox\Deserializer\CollectionDeserializer;

$deserialize = CollectionDeserializer::forThe(Numbers::class);
$numbers = $deserialize->from([10, 11, 12]);

assert($numbers instanceof Numbers);
assert(count($numbers) === 3);
assert($numbers[0] === 10);
assert($numbers[1] === 11);
assert($numbers[2] === 12);
```

In cases where no deserialization is needed, but a Deserializer is expected, 
one can use the `ArrayDeserializer`:

```php
<?php
use Stratadox\Deserializer\ArrayDeserializer;

$deserialize = ArrayDeserializer::make();
$input = ['foo', 'bar'];
$output = $deserialize->from($input);

assert($input === $output);
```

## What else can it do?

By default, the collection deserializer uses a `CollectionHydrator` and the 
object deserializer uses an `ObjectHydrator` for simple objects, or a 
`ReflectiveHydrator` when inheritance is involved.

This default behaviour can be changed by injecting custom (potentially decorated)
hydrators and instantiators:

```php
<?php
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Instantiator\Instantiator;
use Stratadox\Hydrator\ObjectHydrator;

$deserialize = ObjectDeserializer::using(
    Instantiator::forThe(Foo::class),
    ObjectHydrator::default()
);
```

In some cases, the input can vary in such a way that a different deserializer is
required for different types of data.

For instance, when the input data represents an inheritance structure:

```php
<?php
use Stratadox\Deserializer\ForDataSets;
use Stratadox\Deserializer\Condition\HaveTheDiscriminatorValue;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Deserializer\OneOfThese;

$deserialize = OneOfThese::deserializers(
    ForDataSets::that(
        HaveTheDiscriminatorValue::of('type', 'A'), 
        ObjectDeserializer::forThe(ChildA::class)
    ),
    ForDataSets::that(
        HaveTheDiscriminatorValue::of('type', 'B'), 
        ObjectDeserializer::forThe(ChildB::class)
    )
);

$a = $deserialize->from([
    'type' => 'A',
    'property' => 'value',
]);
$b = $deserialize->from([
    'type' => 'B',
    'attribute' => 'different value',
]);
assert($a instanceof ChildA);
assert($b instanceof ChildB);
```
