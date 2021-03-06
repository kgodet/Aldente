<?php

namespace Aldente;

/**
 *
 * @see Api\Bag
 */
final class Bag implements Api\Bag
{
    /**
     *
     * @var array of elements
     */
    private $elements = array();

    /**
     *
     * @param Api\Bag|Iterator|array $values
     */
    public function __construct($values = array())
    {
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    // Api\Bag implementation

    /**
     *
     * @param mixed $value
     *
     * @return int
     */
    public function multiplicity($value)
    {
        return count(array_keys($this->elements, $value));
    }

    /**
     * Returns the Set of unique members that represent all members in the bag
     *
     * @return Set
     */
    public function toSet()
    {
        return new Set(array_unique($this->elements));
    }

    // Api\Set operations

    /**
     *
     * @param scalar $value
     *
     * @return Bag
     */
    public function add($value)
    {
        $this->elements[] = $value;

        return $this;
    }

    /**
     *
     * @param scalar $value
     *
     * @return boolean
     */
    public function has($value)
    {
        return in_array($value, $this->elements);
    }

    /**
     *
     * @param scalar $value
     *
     * @return Bag
     */
    public function remove($value)
    {
        foreach (array_keys($this->elements, $value) as $key) {
            unset($this->elements[$key]);
        }

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return 0 == count($this->elements);
    }

    /**
     * Removes random value(s) from the set and returns it
     *
     * Returns a single value if called without argument, or an array else.
     *
     * @param int $quantity How many elements to pick
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     */
    public function pick($quantity = 1)
    {
        if (!is_int($quantity)) {
            throw new \InvalidArgumentException('You can only pick a natural number of values.');
        }

        if ($quantity < 1) {
            throw new \OutOfBoundsException('You can only pick a strictly positive number of values.');
        }

        $values = [];
        foreach (array_rand($this->elements, $quantity) as $key) {
            $values[] = $this->elements[$key];
            unset($this->elements[$key]);
        }

        return func_num_args() ? $values: reset($values);
    }

    /**
     * Returns the set of values including all values from both of these sets.
     *
     * @param Api\Bag|Iterator|array $set
     *
     * @return Api\Bag
     */
    public function union($set)
    {
        $unionBag = new Bag($this);

        foreach ($set as $value) {
            $unionBag->add($value);
        }

        return $unionBag;
    }

    /**
     * Returns the set of values that are in both of these sets.
     *
     * @param Api\Bag|Iterator|array $set
     *
     * @return Api\Bag
     */
    public function intersection($set)
    {
        $intersectionBag = new Bag();

        foreach ($set as $value) {
            if ($this->has($value)) {
                $intersectionBag->add($value);
            }
        }

        return $intersectionBag;
    }

    /**
     * Returns the set of values that are in this set, excluding the values that
     * are also in the other set.
     *
     * @param Api\Bag|Iterator|array $set
     *
     * @return Api\Bag
     */
    public function difference($set)
    {
        $differenceBag = new Bag();

        foreach ($this as $value) {
            if ($set->has($value)) {
                $differenceBag->add($value);
            }
        }

        return $differenceBag;
    }

    /**
     * Returns the set of values that are only in one of these sets.
     *
     * @param Api\Bag|Iterator|array $set
     *
     * @return Api\Bag
     */
    public function symmetricDifference($set);

    /**
     * Returns the set of values that are only in one of these sets.
     *
     * @param Api\Bag|Iterator|array $set
     *
     * @return Api\Bag
     */
    public function equals($set);

    // Countable implementation

    /**
     *
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    // IteratorAggregate implementation

    /**
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    // Collection pipeline

    /**
     * Maps all values in the Bag with the callback
     *
     * @param callable $callback
     *
     * @return Bag
     */
    public function map($callback)
    {
        $this->elements = array_map($callback, $this->elements);

        return $this;
    }

    /**
     * Keep each value from this collection that passes the given test
     *
     * @param callable $callback
     *
     * @return Bag
     */
    public function select($callback)
    {
        $this->elements = array_filter($this->elements, $callback);

        return $this;
    }

    /**
     * Aggregates every value in this collection with the result collected up to
     * that index.
     *
     * @param callable $callback
     * @param mixed $basis
     *
     * @return mixed
     */
    public function reduce($callback, $basis = null)
    {
        return array_reduce($this->elements, $callback, $basis);
    }

    // Utilities

    /**
     * Returns an array of each value in this collection.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * Empties the Bag of any value
     *
     * @return Bag
     */
    public function clear()
    {
        $this->elements = [];
    }
}
