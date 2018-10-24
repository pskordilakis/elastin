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
Builder::create()
    ->index('index_1')
    // or
    ->indices(['index_2', 'index_3']);
```

### Add bool query

Supported bool queries: must, filter, mustNot, should

``` php
Builder::create()
    ->filter("term", [ "tag" => "search" ]);
```

### Build query

Build method return an array representation of query that can be past to elasticsearch-php client.

``` php
$query = Builder::create()
    ->index('index_1')
    ->filter("term", [ "tag" => "search" ])
    ->build();

$client->search($query);