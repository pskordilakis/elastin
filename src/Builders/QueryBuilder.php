<?php

namespace Elastin\Builders;

use Elastin\Interfaces\Builder;
use Elastin\Query;
use stdClass;

class QueryBuilder implements Builder
{
    /**
     * @var Query $query
     */
    private $query;

    /**
     * @var array sources
     */
    private $sources = [];

    /**
     * @var mixed matchAll
     */
    private $matchAll = null;

    /**
     * @var mixed matchNone
     */
    private $matchNone = null;

    /**
     * @var mixed from
     */
    private $from = null;

    /**
     * @var mixed size
     */
    private $size = null;


    /**
     * @var array mustClauses
     */
    private $mustClauses = [];

    /**
     * @var array mustNotClauses
     */
    private $mustNotClauses = [];

    /**
     * @var array filterClauses
     */
    private $filterClauses = [];

    /**
     * @var array shouldClauses
     */
    private $shouldClauses = [];

    /**
     * @var array ranges
     */
    private $ranges = [];

    /**
     * @var array aggregations
     */
    private $aggregations = [];

    /**
     * @var array sorting
     */
    private $sorting = [];

    /**
     * @var mixed explain
     */
    private $explain = null;

    /**
     * @var mixed version
     */
    private $version = null;

    public function __construct()
    {
        $this->query = new Query();
    }

    public function source(array $includes, array $excludes = null): QueryBuilder
    {
        $value = ($includes && !$excludes)
            ? $includes
            : [ 'includes' => $includes, 'excludes' => $excludes ];

        $this->sources = array_merge($this->sources, $value);

        return $this;
    }

    public function all(?float $boost = null): QueryBuilder
    {
        $this->matchAll = $boost
            ? [ 'boost' => $boost ]
            : new stdClass();

        return $this;
    }

    public function none(): QueryBuilder
    {
        $this->matchNone = new stdClass();

        return $this;
    }

    public function from(int $from): QueryBuilder
    {
        $this->from = $from;

        return $this;
    }

    public function size(int $size): QueryBuilder
    {
        $this->size = $size;

        return $this;
    }

    public function must(string $key, array $predicate): QueryBuilder
    {
        $this->mustClauses[] = [ $key => $predicate ];

        return $this;
    }

    public function mustNot(string $key, array $predicate): QueryBuilder
    {
        $this->mustNotClauses[] = [ $key => $predicate ];

        return $this;
    }

    public function filter(string $key, array $predicate): QueryBuilder
    {
        $this->filterClauses[] = [ $key => $predicate ];

        return $this;
    }

    public function should(string $key, array $predicate): QueryBuilder
    {
        $this->shouldClauses[] = [ $key => $predicate ];

        return $this;
    }

    public function range(string $key, array $predicate): QueryBuilder
    {
        $this->ranges[$key] = $predicate;

        return $this;
    }

    public function aggregation($key, $value): QueryBuilder
    {
        $this->aggregations[$key] = $value;

        return $this;
    }

    public function sort(string $field, $order): QueryBuilder
    {
        $this->sorting[] = [ $field => [ 'order' => $order ]];

        return $this;
    }

    public function explain(bool $enable = true): QueryBuilder
    {
        $this->explain = $enable;

        return $this;
    }

    public function version(bool $enable = true): QueryBuilder
    {
        $this->version = $enable;

        return $this;
    }

    public function _buildSources()
    {
        if (count($this->sources) > 0) {
            $this->query['_source'] = $this->sources;
        }
    }

    public function _buildMatch()
    {
        if (null !== $this->matchAll) {
            $this->query['query.match_all'] = $this->matchAll;
        }

        if (null !== $this->matchNone) {
            $this->query['query.match_none'] = $this->matchNone;
        }
    }

    public function _buildPagination()
    {
        if (null !== $this->from) {
            $this->query['from'] = $this->from;
        }

        if (null !== $this->size) {
            $this->query['size'] = $this->size;
        }
    }

    public function _buildBoolQueries()
    {
        if (count($this->mustClauses) > 0) {
            $this->query['query.bool.must'] = $this->mustClauses;
        }

        if (count($this->mustNotClauses) > 0) {
            $this->query['query.bool.must_not'] = $this->mustNotClauses;
        }

        if (count($this->filterClauses) > 0) {
            $this->query['query.bool.filter'] = $this->filterClauses;
        }

        if (count($this->shouldClauses) > 0) {
            $this->query['query.bool.should'] = $this->shouldClauses;
        }
    }

    private function _buildRangeQueries()
    {
        if (count($this->ranges) > 0) {
            $this->query['query.range'] = $this->ranges;
        }
    }

    private function _buildAggregations()
    {
        if (count($this->aggregations) > 0) {
            $this->query['aggs'] = $this->aggregations;
        }
    }

    public function _buildSorting()
    {
        if ($this->sorting) {
            $this->query['sort'] = $this->sorting;
        }
    }

    public function _buildExplain()
    {
        if (null !== $this->explain) {
            $this->query['explain'] = $this->explain;
        }
    }

    public function _buildVersion()
    {
        if (null !== $this->version) {
            $this->query['version'] = $this->version;
        }
    }

    public function build(): array
    {
        $this->_buildMatch();
        $this->_buildBoolQueries();
        $this->_buildRangeQueries();
        $this->_buildAggregations();
        $this->_buildPagination();
        $this->_buildSorting();
        $this->_buildSources();
        $this->_buildExplain();
        $this->_buildVersion();

        return $this->query->all();
    }
}
