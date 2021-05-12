<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->_whereLike();
        $this->_paginate();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    private function _whereLike()
    {
        // whereLike
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        Str::contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });
            return $this;
        });
    }

    private function _paginate()
    {
        // Enable pagination
        if (!Collection::hasMacro('paginate')) {
            $pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '');
            $hostInfo = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            $actualLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$hostInfo$pathInfo";
            Collection::macro('paginate', function ($perPage = 15, $page = null, $options = []) use ($actualLink) {
                $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                return (new LengthAwarePaginator(
                    $this->forPage($page, $perPage)
                        ->values()
                        ->all(),
                    $this->count(),
                    $perPage,
                    $page,
                    $options
                ))->withPath($actualLink);
            });
        }
    }

    private function _selectRelationship()
    {
        Builder::macro('selectRelationship', function ($relationship) {
            $this->when(
                \Str::contains($relationship, ':'),
                function (Builder $query) use ($relationship) {
                    list($relationship, $select) = explode(':', $relationship);
                    $query->with([
                        'roles' => function ($query) use ($select) {
                            $query->select(explode(',', $select));
                        },
                    ]);
                },
                function (Builder $query) use ($relationship) {
                    $query->with($relationship);
                }
            );
            return $this;
        });
    }
}
