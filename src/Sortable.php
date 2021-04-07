<?php

namespace TheCodeMill\EloquentSort;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    /**
     * Return the sortable direction constants.
     *
     * @return array
     */
    public static function sortDirections()
    {
        return [
            'asc',
            'desc',
        ];
    }

    /**
     * Return the default sort direction.
     *
     * @return string
     */
    public static function defaultSortDir()
    {
        return 'asc';
    }

    /**
     * Scope a query to sort by a given sortable key and direction.
     *
     * Eg. App\User::sort('name', 'desc')->get();
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort(Builder $query, string $key, string $direction)
    {
        $sortHandlers = static::sortables();

        if (!in_array($direction, static::sortDirections())) {
            $direction = static::defaultSortDir();
        }

        if (array_key_exists($key, $sortHandlers)) {
            $sortHandlers[$key]($query, $direction);
        }

        return $query;
    }

    /**
     * Return the sort key and direction from a key => value array.
     *
     * This is a useful for detecting sort parameters in an input array. Most often used to append sort parameters to
     * rendered pagination links.
     *
     * Eg. $paginator->appends(App\Model::validSorting(request()->all()))->links()
     *
     * @param array $sorting
     * @return array
     */
    public static function validSorting(array $sorting = [])
    {
        return array_filter($sorting, function ($value, $key) {
            return in_array($key, ['sort_key', 'sort_dir']);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Return whether a particular sort key is active within a set of request parameters.
     *
     * @param string $key
     * @param array $params
     * @return bool
     */
    public static function sortKeyActive($key, array $params)
    {
        return array_key_exists('sort_key', $params) && $params['sort_key'] == $key;
    }

    /**
     * Reverse a given sort direction.
     *
     * @param string $dir
     * @return string
     */
    public static function reverseSortDir($dir)
    {
        return ($dir == 'asc') ? 'desc' : 'asc';
    }

    /**
     * Turn a sort key and direction into an array of request parameters.
     *
     * @param string $key
     * @param string $dir
     * @return array
     */
    public static function sortParams($key, $dir = 'asc')
    {
        return [
            'sort_key' => $key,
            'sort_dir' => $dir,
        ];
    }

    /**
     * Switch the sort direction with an array of sorting parameters.
     *
     * @param array $sorting
     * @return array
     */
    public static function switchSorting(array $sorting = [])
    {
        $validSorting = static::validSorting($sorting);

        if (array_key_exists('sort_dir', $validSorting)) {
            $validSorting['sort_dir'] = static::reverseSortDir($validSorting['sort_dir']);
        }

        return $validSorting;
    }

    /**
     * Return the model's sortable handlers in key => closure form.
     *
     * Eg. return [
     *     'max' => function ($query, $direction) {
     *         $query->selectRaw('MAX(column) AS max_column');
     *
     *         return $query->orderBy('max_column', $direction);
     *     }
     * ];
     *
     * @return array
     */
    public static function sortables()
    {
        return [];
    }
}
