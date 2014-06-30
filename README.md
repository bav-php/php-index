# About PHP-Index

This library provides an API to perform binary search operations on a sorted
index. The index can be a XML document, an CSV document, or an arbitrary text
file where the key has a fixed position. You can easily implement your own
index. This API comes handy on any sorted data structure where realtime search
operations are necessary without the detour of a DBS import.


# Installation

Use [Composer](https://getcomposer.org/):

```json
{
    "require": {
        "malkusch/php-index": "0.0.1"
    }
}
```


# Usage

Have a look at the docs/examples/ folder. You can find there examples for each
implemented index structure.

[![Build Status](https://travis-ci.org/malkusch/php-index.svg)](https://travis-ci.org/malkusch/php-index)