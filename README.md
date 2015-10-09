# Aldente
Abstract Data Types library


Set
===

A set is unsorted and unstable thus cannot support `pop()` and `shift()`.

A set structure:

  - is unstable (input order is not retained)
  - is not ordered
  - is not associative
  - cannot contain duplicates

Object API:

    $set = new Set();
    $set->add('foo'); // returns $set
    $set->has('foo');  // returns true
    $set->add('foo'); // throws AlreadyInSetException
    echo count($set); // 1
    $set->remove('foo');  // returns $set

Array API:

