<?php

namespace Shankar\LaravelBasicSetting\Traits;

trait CanSearch
{
    public function scopeSearch($query, $search)
    {
        $columns = $this->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($this->getTable());

        return $query->where(function ($query) use ($columns, $search) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%{$search}%");
            }
        });
    }

    /**
     * Scope to search through specified relationship fields
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchRelation($query, $search, array $relations)
    {
        return $query->where(function ($query) use ($search, $relations) {
            foreach ($relations as $relation => $fields) {
                $query->orWhereHas($relation, function ($query) use ($fields, $search) {
                    $query->where(function ($query) use ($fields, $search) {
                        foreach ($fields as $field) {
                            $query->orWhere($field, 'LIKE', "%{$search}%");
                        }
                    });
                });
            }
        });
    }
}
