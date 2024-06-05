<?php

namespace Fleetbase\Expansions;

use Fleetbase\Build\Expansion;
use Fleetbase\Models\Company;
<<<<<<< HEAD
use Fleetbase\Models\File;
use Illuminate\Support\Str;

/**
 * Expands the Illuminate\Http\Request class with additional helper methods.
 *
=======
use Illuminate\Support\Str;

/**
>>>>>>> origin/main
 * @mixin \Illuminate\Support\Facades\Request
 */
class Request implements Expansion
{
    /**
<<<<<<< HEAD
     * Specifies the class this expansion targets.
     *
     * @return string the name of the class to expand
=======
     * Get the target class to expand.
     *
     * @return string|Class
>>>>>>> origin/main
     */
    public static function target()
    {
        return \Illuminate\Support\Facades\Request::class;
    }

    /**
<<<<<<< HEAD
     * Retrieves the current company based on the session data.
     *
     * @return \Closure returns a closure that resolves to a Company instance or null
=======
     * Extends Request to find the current organization/company.
     *
     * @return Company|null
>>>>>>> origin/main
     */
    public function company()
    {
        return function () {
            /** @var \Illuminate\Http\Request $this */
            if ($this->session()->has('company')) {
                return Company::find($this->session()->get('company'));
            }

            return null;
        };
    }

    /**
<<<<<<< HEAD
     * Attempts to retrieve the first available parameter from a specified set.
     *
     * @return \Closure returns a closure that checks for the presence of parameters
     *                  in a specific order and returns the value of the first parameter found
     */
    public function or()
    {
        return function (array $params = [], $default = null) {
            /** @var \Illuminate\Http\Request $this */
=======
     * Iterates request params until a param is found.
     *
     * @return Closure
     */
    public function or()
    {
        /*
         * Iterates request params until a param is found.
         *
         * @param array $params
         * @param mixed $default
         * @return mixed
         */
        return function (array $params = [], $default = null) {
            /* @var \Illuminate\Http\Request $this */
>>>>>>> origin/main
            foreach ($params as $param) {
                if ($this->has($param)) {
                    return $this->input($param);
                }
            }

            return $default;
        };
    }

    /**
<<<<<<< HEAD
     * Converts a specified request parameter into an array by splitting it by commas.
     *
     * @return \Closure returns a closure that splits a string parameter into an array,
     *                  or directly returns the array parameter
     */
    public function array()
    {
=======
     * Retrieve input from the request as a array.
     *
     * @return Closure
     */
    public function array()
    {
        /*
         * Retrieve input from the request as a array.
         *
         * @param string $param
         * @return array
         */
>>>>>>> origin/main
        return function (string $param) {
            /** @var \Illuminate\Http\Request $this */
            if (is_string($this->input($param)) && Str::contains($this->input($param), ',')) {
                return explode(',', $this->input($param));
            }

            return (array) $this->input($param, []);
        };
    }

    /**
<<<<<<< HEAD
     * Checks if a specified parameter is a string.
     *
     * @return \Closure returns a closure that determines if a parameter is a string
=======
     * Check if param is string value.
     *
     * @return Closure
>>>>>>> origin/main
     */
    public function isString()
    {
        return function ($param) {
<<<<<<< HEAD
            /** @var \Illuminate\Http\Request $this */
=======
            /*
             * Context.
             *
             * @var \Illuminate\Support\Facades\Request $this
             */
>>>>>>> origin/main
            return $this->has($param) && is_string($this->input($param));
        };
    }

    /**
<<<<<<< HEAD
     * Checks if a specified parameter is an array.
     *
     * @return \Closure returns a closure that determines if a parameter is an array
=======
     * Check if param is array value.
     *
     * @return Closure
>>>>>>> origin/main
     */
    public function isArray()
    {
        return function ($param) {
<<<<<<< HEAD
            /** @var \Illuminate\Http\Request $this */
=======
            /*
             * Context.
             *
             * @var \Illuminate\Support\Facades\Request $this
             */
>>>>>>> origin/main
            return $this->has($param) && is_array($this->input($param));
        };
    }

    /**
<<<<<<< HEAD
     * Checks if a specific value exists within an array parameter.
     *
     * @return \Closure returns a closure that checks if a value is present in an array parameter
=======
     * Check value exists in request array param.
     *
     * @return Closure
>>>>>>> origin/main
     */
    public function inArray()
    {
        return function ($param, $needle) {
<<<<<<< HEAD
            /** @var \Illuminate\Http\Request $this */
=======
            /**
             * Context.
             *
             * @var \Illuminate\Support\Facades\Request $this
             */
>>>>>>> origin/main
            $haystack = (array) $this->input($param, []);

            if (is_array($haystack)) {
                return in_array($needle, $haystack);
            }

            return false;
        };
    }

    /**
<<<<<<< HEAD
     * Retrieves an integer value from a specified request parameter.
     *
     * @return \Closure returns a closure that fetches an integer from the request
     */
    public function integer()
    {
        return function (string $key, $default = 0) {
            /** @var \Illuminate\Http\Request $this */
=======
     * Retrieve input from the request as a integer.
     *
     * @return Closure
     */
    public function integer()
    {
        /*
         * Retrieve input from the request as a integer.
         *
         * @param string $key
         * @return array
         */
        return function (string $key, $default = 0) {
            /* @var \Illuminate\Http\Request $this */
>>>>>>> origin/main
            return intval($this->input($key, $default));
        };
    }

    /**
<<<<<<< HEAD
     * Removes a specified parameter from the request.
     *
     * @return \Closure returns a closure that removes a parameter from the request
     */
    public function removeParam()
    {
        return function (string $key) {
            /** @var \Illuminate\Http\Request $this */
=======
     * Removes a param from the request.
     *
     * @return Closure
     */
    public function removeParam()
    {
        /*
         * Retrieve input from the request as a integer.
         *
         * @param string $key
         * @return array
         */
        return function (string $key) {
            /* @var \Illuminate\Http\Request $this */
>>>>>>> origin/main
            return $this->request->remove($key);
        };
    }

    /**
<<<<<<< HEAD
     * Retrieves the search query from the request, with prioritization over multiple possible keys.
     *
     * @return \Closure returns a closure that fetches a search query parameter, prioritizing
     *                  specific keys and handling potential casing and encoding issues
     */
    public function searchQuery()
    {
=======
     * Retrieves the search query parameter.
     *
     * @return Closure
     */
    public function searchQuery()
    {
        /*
         * Retrieve the search query parameter.
         *
         * @return string
         */
>>>>>>> origin/main
        return function () {
            /** @var \Illuminate\Http\Request $this */
            $searchQueryParam = $this->or(['query', 'searchQuery', 'nestedQuery']);

            if (is_string($searchQueryParam)) {
                return urldecode(strtolower($searchQueryParam));
            }

            return $searchQueryParam;
        };
    }

    /**
<<<<<<< HEAD
     * Fetches File models based on UUIDs provided in a specified request parameter.
     *
     * @return \Closure returns a closure that retrieves a collection of File models from UUIDs specified in the request
     */
    public function resolveFilesFromIds()
    {
        return function (string $param = 'files') {
            /** @var \Illuminate\Http\Request $this */
            return File::fromRequest($this, $param);
        };
    }

    /**
     * Retrieves all request parameters except for those related to Fleetbase's global filters.
     *
     * @return \Closure returns a closure that filters out global parameters and retrieves the rest
=======
     * Returns all Fleetbase global filters.
     *
     * @return Closure
>>>>>>> origin/main
     */
    public function getFilters()
    {
        return function (?array $additionalFilters = []) {
            $defaultFilters = [
                'within',
                'with',
                'without',
                'without_relations',
                'coords',
                'boundary',
                'page',
                'nestedPage',
                'offset',
                'limit',
                'nestedLimit',
                'perPage',
                'per_page',
                'singleRecord',
                'single',
                'query',
                'searchQuery',
                'nestedQuery',
                'columns',
                'distinct',
                'sort',
                'nestedSort',
                'before',
                'after',
                'on',
                'global',
            ];
            $filters = is_array($additionalFilters) ? array_merge($defaultFilters, $additionalFilters) : $defaultFilters;

<<<<<<< HEAD
            /** @var \Illuminate\Http\Request $this */
=======
            /* @var \Illuminate\Http\Request $this */
>>>>>>> origin/main
            return $this->except($filters);
        };
    }
}
