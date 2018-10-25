<?php

namespace Elastin;

use Elastin\Builders\QueryBuilder;
use stdClass;

class Builder
{
    /**
     * @var Query $query
     */
    private $query;

    /**
     * @var array $queries
     */
    private $queries = [];

    /**
     * @var array $indices
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
     * Append a new query
     *
     * @param \Elastin\Builders\QueryBuilder $query
     *
     * @return \Elastin\Builder
     */
    public function appendQuery(?QueryBuilder $query = null): Builder
    {
        if ($query === null) {
            $this->queries[] = new QueryBuilder();
        } else {
            $this->queries[] = $query;
        }

        return $this;
    }

    /**
     * appendQuery alias
     *
     * @param \Elastin\Builders\QueryBuilder $query
     *
     * @return \Elastin\Builder
     */
    public function query(?QueryBuilder $query = null): Builder
    {
        $this->appendQuery($query);

        return $this;
    }

    /**
     * Add index
     *
     * @param string $index
     *
     * @return \Elastin\Builder
     */
    public function index(string $index): Builder
    {
        $this->indices[] = $index;

        return $this;
    }

    /**
     * Add multiple indexes
     *
     * @param array $indices
     *
     * @return \Elastin\Builder
     */
    public function indices(array $indices): Builder
    {
        $this->indices = array_merge($this->indices, $indices);

        return $this;
    }

    /**
     * Trap any method call that is not defined in the Builder
     * and call it at last query object
     *
     * @param string $method
     * @param array $arguments
     *
     * @return \Elastin\Builder
     */
    public function __call(string $method, array $arguments): Builder
    {
        if (count($this->queries) !== 0) {
            $queryBuilder = $this->queries[count($this->queries) - 1];

            $callback = [$queryBuilder, $method];
            if (is_callable($callback)) {
                call_user_func_array([$queryBuilder, $method], $arguments);
            }
        }

        return $this;
    }

    /**
     * Add all indices to query
     *
     * @return \Elastin\Builder
     */
    public function _buildIndices(): Builder
    {
        if (count($this->indices) > 0) {
            $indices = implode(',', $this->indices);

            $this->query['index'] = $indices;
        }

        return $this;
    }

    /**
     * Build all queries
     *
     * @return \Elastin\Builder
     */
    private function _buildQueries(): Builder
    {
        if (count($this->queries) === 1) {
            // Single query is an object
            $this->query['body'] = $this->queries[0]->build();
        } elseif (count($this->queries) > 1) {
            // multiple queries is an array
            $this->query['body'] = array_map(function ($query) {
                return $query->build();
            }, $this->queries);
        }

        return $this;
    }

    /**
     * Build final query/queries
     *
     * @return array
     */
    public function build(): array
    {
        $this
            ->_buildIndices()
            ->_buildQueries();

        return $this->query->all();
    }

    /**
     * Build query and return it as json
     *
     * @return string
     */
    public function buildJson(): ?string
    {
        $query = $this->build();
        $json = json_encode($query);

        if ($json === false) {
            return null;
        }

        return $json;
    }

    /**
     * Build query and return only the body,
     * as it would be sent in elasticsearch
     * server.
     */
    public function buildBodyJson(): ?string
    {
        $query = $this->build();
        $json = json_encode($query['body']);

        if ($json === false) {
            return null;
        }

        return $json;
    }
}
