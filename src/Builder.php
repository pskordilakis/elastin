<?php

namespace Elastin;

use Elastin\Builders\QueryBuilder;
use stdClass;

class Builder
{
    /**
     * @var queries
     */
    private $query;

    /**
     * @var queries
     */
    private $queries = [];

    /**
     * @var indices
     */
    private $indices = [];

    public function __construct()
    {
        $this->query = new Query;
    }

    public static function create()
    {
        $builder = new self();
        $builder->query();

        return $builder;
    }

    /**
     * Add new query
     */
    public function query(?QueryBuilder $query = null): Builder
    {
        $this->appendQuery($query);

        return $this;
    }

    public function appendQuery(?QueryBuilder $query = null): Builder
    {
        if ($query === null) {
            $this->queries[] = new QueryBuilder();
        } else {
            $this->queries[] = $query;
        }

        return $this;
    }

    public function index(string $index): Builder
    {
        $this->indices[] = $index;

        return $this;
    }

    public function indices(array $indices): Builder
    {
        $this->indices = array_merge($this->indices, $indices);

        return $this;
    }

    public function __call(string $name, array $arguments): Builder
    {
        if (count($this->queries) !== 0) {
            $queryBuilder = $this->queries[count($this->queries) - 1];

            call_user_func_array([$queryBuilder, $name], $arguments);
        }

        return $this;
    }

    public function _buildIndices()
    {
        if (count($this->indices) > 0) {
            $indices = implode(',', $this->indices);

            $this->query['index'] = $indices;
        }
    }

    private function _buildQueries()
    {
        if (count($this->queries) === 1) {
            $this->query['body'] = $this->queries[0]->build();
        } elseif (count($this->queries) > 1) {
            $this->query['body'] = array_map(function ($query) {
                return $query->build();
            }, $this->queries);
        }
    }

    public function build(): array
    {
        $this->_buildIndices();
        $this->_buildQueries();

        return $this->query->all();
    }

    public function buildJson()
    {
        $query = $this->build();

        return json_encode($query);
    }
}
