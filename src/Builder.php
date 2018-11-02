<?php

namespace Elastin;

use Elastin\Builders\QueryBuilder;
use stdClass;

class Builder
{
    /**
     * @var array $queries
     */
    private $queries = [];

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
     * Check if we do not have any query
     *
     * @return bool
     */
    public function empty(): bool
    {
        return count($this->queries) === 0;
    }

    /**
     * Check if we have single query.
     * Used with search method/endpoint.
     *
     * @return bool
     */
    public function single()
    {
        return count($this->queries) === 1;
    }

    /**
     * Check if we have multiple queries.
     * Used with msearch method/endpoint.
     *
     * @return bool
     */
    public function multiple(): bool
    {
        return count($this->queries) > 1;
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
     * Build final query/queries
     *
     * @return array
     */
    public function build(): array
    {
        $queries = array_map(function ($query) {
            return $query->build();
        }, $this->queries);

        if (count($queries) <= 0) {
            // empty query
            return [
                'body' => new stdClass()
            ];
        }

        if (count($queries) === 1) {
            // Single query
            $query = $queries[0];

            return array_merge($query['headers'], [ 'body' => $query['body'] ]);
        }


        if (count($queries) > 1) {
            // multiple queries is an array
            return array_reduce($queries, function ($acc, $query) {
                $acc['body'] = array_merge($acc['body'], array_values($query));

                return $acc;
            }, [ 'body' => [] ]);
        }

        return [];
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
