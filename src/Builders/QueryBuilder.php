<?php

namespace Elastin\Builders;

use Elastin\Interfaces\Builder;
use Elastin\Query;
use Elastin\Container;
use stdClass;

class QueryBuilder implements Builder
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var array
     */
    private $indices = [];

    /**
     * @var array
     */
    private $sources = [];

    /**
     * @var mixed
     */
    private $matchAll = null;

    /**
     * @var mixed
     */
    private $matchNone = null;

    /**
     * @var mixed
     */
    private $from = null;

    /**
     * @var mixed
     */
    private $size = null;


    /**
     * @var array
     */
    private $mustClauses = [];

    /**
     * @var array
     */
    private $mustNotClauses = [];

    /**
     * @var array
     */
    private $filterClauses = [];

    /**
     * @var array
     */
    private $shouldClauses = [];

    /**
     * @var array
     */
    private $ranges = [];

    /**
     * @var \Elastin\Container
     */
    private $aggregations = null;

    /**
     * @var array
     */
    private $sorting = [];

    /**
     * @var mixed
     */
    private $explain = null;

    /**
     * @var mixed
     */
    private $version = null;

    public function __construct()
    {
        $this->aggregations = new Container();
    }

    /**
     * Add index
     *
     * @param string $index
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function index(string $index): QueryBuilder
    {
        $this->indices[] = $index;

        return $this;
    }

    /**
     * Add multiple indexes
     *
     * @param array $indices
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function indices(array $indices): QueryBuilder
    {
        $this->indices = array_merge($this->indices, $indices);

        return $this;
    }

    /**
     * @param array $includes
     * @param array $excludes
     *
     * @return \Elastin\Builders\QueryBuilder
     */

    public function source(array $includes, array $excludes = null): QueryBuilder
    {
        $value = ($includes && !$excludes)
            ? $includes
            : [ 'includes' => $includes, 'excludes' => $excludes ];

        $this->sources = array_merge($this->sources, $value);

        return $this;
    }

    /**
     * @param float $boost
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function all(?float $boost = null): QueryBuilder
    {
        $this->matchAll = $boost
            ? [ 'boost' => $boost ]
            : new stdClass();

        return $this;
    }

    /**
     * @return \Elastin\Builders\QueryBuilder
     */
    public function none(): QueryBuilder
    {
        $this->matchNone = new stdClass();

        return $this;
    }

    /**
     * @param int $from
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function from(int $from): QueryBuilder
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param int $size
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function size(int $size): QueryBuilder
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @param string $key
     * @param array $predicate
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function must(string $key, array $predicate): QueryBuilder
    {
        $this->mustClauses[] = [ $key => $predicate ];

        return $this;
    }

    /**
     * @param string $key
     * @param array $predicate
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function filter(string $key, array $predicate): QueryBuilder
    {
        $this->filterClauses[] = [ $key => $predicate ];

        return $this;
    }

    /**
     * @param string $field
     * @param mixed $value
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function where(string $field, $value): QueryBuilder
    {
        return $this->filter('term', [ $field => $value ]);
    }

    /**
     * @param string $field
     * @param mixed $value
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function whereNot(string $field, $value): QueryBuilder
    {
        return $this->mustNot('term', [ $field => $value ]);
    }

    /**
     * @param string $field
     * @param array $values
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function whereIn(string $field, array $values): QueryBuilder
    {
        return $this->filter('terms', [ $field => $values ]);
    }

    /**
     * @param string $field
     * @param mixed $gte
     * @param mixed $lte
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function whereBetween(string $field, $gte, $lte): QueryBuilder
    {
        return $this->filter('range', [ $field => [
            'gte' => $gte,
            'lte' => $lte,
         ]]);
    }

    /**
     * @param string $key
     * @param array $predicate
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function mustNot(string $key, array $predicate): QueryBuilder
    {
        $this->mustNotClauses[] = [ $key => $predicate ];

        return $this;
    }

    /**
     * @param string $key
     * @param array $predicate
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function should(string $key, array $predicate): QueryBuilder
    {
        $this->shouldClauses[] = [ $key => $predicate ];

        return $this;
    }

    /**
     * @param string $key
     * @param array $predicate
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function range(string $key, array $predicate): QueryBuilder
    {
        $this->ranges[$key] = $predicate;

        return $this;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function aggregation($key, $value): QueryBuilder
    {
        $this->aggregations[$key] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function count(string $name, string $field): QueryBuilder
    {
        return $this->aggregation($name, [ 'value_count' => [ 'field' => $field ] ]);
    }

    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function cardinality(string $name, string $field): QueryBuilder
    {
        return $this->aggregation($name, [ 'cardinality' => [ 'field' => $field ] ]);
    }

    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function sum(string $name, string $field): QueryBuilder
    {
        return $this->aggregation($name, [ 'sum' => [ 'field' => $field ] ]);
    }

    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function groupBy(string $name, string $field): QueryBuilder
    {
        return $this->aggregation($name, [ 'terms' => [ 'field' => $field ] ]);
    }

    /**
     * @param string $name
     * @param string $field
     * @param array|null $options
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function timeSeries(string $name, string $field, ?array $options): QueryBuilder
    {
        return $this->aggregation($name, [ 'date_histogram' => array_merge([ 'field' => $field ], $options ?? [])]);
    }

    /**
     * @param string $name
     * @param array $vars
     * @param string $script
     * @param string|null $gap_policy
     * @param string|null $format
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function script(string $name, array $vars, string $script, ?string $gap_policy = null, ?string $format = null): QueryBuilder
    {
        $bucket_script = [ 'bucket_script' => [ 'buckets_path' => $vars, 'script' => $script ]];

        if ($gap_policy) {
            $bucket_script['bucket_script']['gap_policy'] = $gap_policy;
        }

        if ($format) {
            $bucket_script['bucket_script']['format'] = $format;
        }

        return $this->aggregation($name, $bucket_script);
    }

    /**
     * @param string $field
     * @param string $order
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function sort(string $field, string $order): QueryBuilder
    {
        $this->sorting[] = [ $field => [ 'order' => $order ]];

        return $this;
    }

    /**
     * @param bool $enable
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function explain(bool $enable = true): QueryBuilder
    {
        $this->explain = $enable;

        return $this;
    }

    /**
     * @param bool $enable
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    public function version(bool $enable = true): QueryBuilder
    {
        $this->version = $enable;

        return $this;
    }

    /**
     * Add all indices to query
     *
     * @return \Elastin\Builders\QueryBuilder
     */
    private function _buildIndices(): QueryBuilder
    {
        if (count($this->indices) > 0) {
            $indices = implode(',', $this->indices);

            $this->query->headers['index'] = $indices;
        }

        return $this;
    }

    /**
     * @return \Elastin\Builders\QueryBuilder
     */
    private function _buildSources(): QueryBuilder
    {
        if (count($this->sources) > 0) {
            $this->query->body['_source'] = $this->sources;
        }

        return $this;
    }

    /**
     * @return \Elastin\Builders\QueryBuilder
     */
    private function _buildMatch(): QueryBuilder
    {
        if (null !== $this->matchAll) {
            $this->query->body['query.match_all'] = $this->matchAll;
        }

        if (null !== $this->matchNone) {
            $this->query->body['query.match_none'] = $this->matchNone;
        }

        return $this;
    }

    /**
     * @return \Elastin\Builders\QueryBuilder
     */
    private function _buildPagination(): QueryBuilder
    {
        if (null !== $this->from) {
            $this->query->body['from'] = $this->from;
        }

        if (null !== $this->size) {
            $this->query->body['size'] = $this->size;
        }

        return $this;
    }

    /**
     * @return \Elastin\Builders\QueryBuilder
     */
    private function _buildBoolQueries(): QueryBuilder
    {
        if (count($this->mustClauses) > 0) {
            $this->query->body['query.bool.must'] = $this->mustClauses;
        }

        if (count($this->mustNotClauses) > 0) {
            $this->query->body['query.bool.must_not'] = $this->mustNotClauses;
        }

        if (count($this->filterClauses) > 0) {
            $this->query->body['query.bool.filter'] = $this->filterClauses;
        }

        if (count($this->shouldClauses) > 0) {
            $this->query->body['query.bool.should'] = $this->shouldClauses;
        }

        return $this;
    }

    /**
     * @return \Elastin\Builders\QueryBuilder
     */
    private function _buildRangeQueries(): QueryBuilder
    {
        if (count($this->ranges) > 0) {
            $this->query->body['query.range'] = $this->ranges;
        }

        return $this;
    }

    /**
     * @return \Elastin\Builders\QueryBuilder
     */
    private function _buildAggregations(): QueryBuilder
    {
        if (count($this->aggregations) > 0) {
            $this->query->body['aggs'] = $this->aggregations->all();
        }

        return $this;
    }

    /**
     * @return \Elastin\Builders\QueryBuilder
     */
    private function _buildSorting(): QueryBuilder
    {
        if ($this->sorting) {
            $this->query->body['sort'] = $this->sorting;
        }

        return $this;
    }

    /**
     * @return \Elastin\Builders\QueryBuilder
     */
    private function _buildExplain(): QueryBuilder
    {
        if (null !== $this->explain) {
            $this->query->body['explain'] = $this->explain;
        }

        return $this;
    }

    /**
     * @return \Elastin\Builders\QueryBuilder
     */
    private function _buildVersion(): QueryBuilder
    {
        if (null !== $this->version) {
            $this->query->body['version'] = $this->version;
        }

        return $this;
    }

    public function build(): array
    {
        $this->query = new Query;

        $this
            ->_buildIndices()
            ->_buildMatch()
            ->_buildBoolQueries()
            ->_buildRangeQueries()
            ->_buildAggregations()
            ->_buildPagination()
            ->_buildSorting()
            ->_buildSources()
            ->_buildExplain()
            ->_buildVersion();

        return $this->query->all();
    }
}
