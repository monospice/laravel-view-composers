<?php

namespace Monospice\LaravelViewComposers\Interfaces;

/**
 * Binds View Composers and View Creators to views
 *
 * @category Package
 * @package  Monospice\LaravelViewComposers
 * @author   Cy Rossignol <cy@rossignols.me>
 * @license  See LICENSE file
 * @link     https://github.com/monospice/laravel-view-composers
 */
interface ViewBinder
{
    /**
     * Define the namespace to apply to View Composers and Creators
     *
     * @param string $namespace The namespace to apply
     *
     * @return ViewBinder The current ViewBinder instance for method chaining
     */
    public function setNamespace($namespace);

    /**
     * Define the prefix to prepend to views
     *
     * @param string $prefix The prefix to prepend
     *
     * @return ViewBinder The current ViewBinder instance for method chaining
     */
    public function setPrefix($prefix);

    /**
     * Define the views to bind to a View Composer
     *
     * @param string|array $views The view or views to compose
     *
     * @return ViewBinder The current ViewBinder instance for method chaining
     */
    public function compose($views);

    /**
     * Define the views to bind to a View Creator
     *
     * @param string|array $views The view or views to compose with the View
     * Creator
     *
     * @return ViewBinder The current ViewBinder instance for method chaining
     */
    public function create($views);

    /**
     * Bind a View Composer or View Creator to the preceding views
     *
     * @param \Closure|string|array $classesOrFunctions The class names and/or
     * anonymous functions representing a View Composer or Creator
     *
     * @return ViewBinder|array The current ViewBinder instance for method
     * chaining
     */
    public function with($classesOrFunctions);
}
