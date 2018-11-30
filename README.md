# Elastin

Elasticsearch query builder. Intended to be used with [elasticsearch-php](https://github.com/elastic/elasticsearch-php) official client.

## Installation

``` bash
composer require pskordilakis/elastin
```

## Usage

### Create builder instance

``` php
use Elastin\Builder;

$builder = Builder::create();
```

### Set index/indices
``` php
$builder->index('index_1')
```

or

``` php
$builder->indices(['index_2', 'index_3']);
```


### Add bool query

Supported bool queries: must, filter, mustNot, should

``` php
$builder->filter('term', [ 'tag' => 'search' ]);
```

### Aggregations

Add simple aggregations to query

``` php
$builder->aggregation('aggregation_name', [ 'aggregation_type' => $aggregation_body ]);
```

Nested aggregation supported by defining a dot separated name

``` php
$builder->aggregation('parent_aggregation_path.aggregation_name', [ 'aggregation_type' => $aggregation_body ]);
```

### Build query

Build method return an array representation of query that can be past to elasticsearch-php client.

``` php
$query = $builder->build();

$client->search($query);
```

## Aliases

Some aliases are provided for common cases.

Aggregation aliases can be used as nested aggregations by passing a dot separated path in name parameter.

### Where

Define a term filter

``` php
$builder->where($field, $value);
```

### WhereBetween

Define a range filter

``` php
$builder->whereBetween($field, $gte, $lte);
```

### Count

Create a value_count aggregation

``` php
$builder->count($name, $field);
```

### Cardinality

Create a cardinality aggregation

``` php
$builder->cardinality($name, $field);
```

### GroupBy

Create a term aggregation

``` php
$builder->groupBy($name, $field);
```

### TimeSeries

Create a date_histogram aggregation

``` php
$builder->timeSeries($name, $field, $options);
```
