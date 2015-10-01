<?php

namespace Monospice\LaravelViewComposers;

use Illuminate\Contracts\View\Factory as ViewFactory;

use Monospice\LaravelViewComposers\Interfaces;

/**
 * Binds View Composers and View Creators to views
 *
 * @category Package
 * @package  Monospice\LaravelViewComposers
 * @author   Cy Rossignol <cy@rossignols.me>
 * @license  See LICENSE file
 * @link     https://github.com/monospice/laravel-view-composers
 */
class ViewBinder implements Interfaces\ViewBinder
{
    /**
     * The Laravel View Factory to use for binding composers
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $viewFactory;

    /**
     * The namespace to apply to a set of to View Composer or Creator classes
     *
     * @var string
     */
    protected $namespace;

    /**
     * The prefix to prepend to a set of views
     *
     * @var string
     */
    protected $prefix;

    /**
     * View or views to bind to a View Composer
     *
     * @var array
     */
    protected $viewsToCompose;

    /**
     * View or views to bind to a View Creator
     *
     * @var array
     */
    protected $viewsToCreate;

    /**
     * Create a new instance of this class
     *
     * @param \Illuminate\Contracts\View\Factory $viewFactory The Laravel view
     * factory instance used to bind views
     */
    public function __construct(ViewFactory $viewFactory)
    {
        $this->viewFactory = $viewFactory;

        $this->resetViews();
        $this->namespace = '';
        $this->prefix = '';
    }

    // Inherit Doc from Interfaces\ViewBinder
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    // Inherit Doc from Interfaces\ViewBinder
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    // Inherit Doc from Interfaces\ViewBinder
    public function compose($views)
    {
        if (! is_array($views)) {
            $views = func_get_args();
        }

        $this->viewsToCompose[] = $this->addPrefix($views);

        return $this;
    }

    // Inherit Doc from Interfaces\ViewBinder
    public function create($views)
    {
        if (! is_array($views)) {
            $views = func_get_args();
        }

        $this->viewsToCreate[] = $this->addPrefix($views);

        return $this;
    }

    // Inherit Doc from Interfaces\ViewBinder
    public function with($classesOrFunctions)
    {
        if (! is_array($classesOrFunctions)) {
            $classesOrFunctions = func_get_args();
        }

        $classesOrFunctions = $this->addNamespace($classesOrFunctions);

        foreach ($this->viewsToCompose as $view) {
            $this->composeView($view, $classesOrFunctions);
        }

        foreach ($this->viewsToCreate as $view) {
            $this->createView($view, $classesOrFunctions);
        }

        $this->resetViews();

        return $this;
    }

    /**
     * Bind a view to the specified View Composers
     *
     * @param string $view      The view to bind
     * @param array  $composers The View Composers to bind the view to
     *
     * @return void
     */
    protected function composeView($view, array $composers)
    {
        foreach ($composers as $composer) {
            $this->viewFactory->composer($view, $composer);
        }
    }

    /**
     * Bind a view to the specified View Creators
     *
     * @param string $view     The view to bind
     * @param array  $creators The View Creators to bind the view to
     *
     * @return void
     */
    protected function createView($view, array $creators)
    {
        foreach ($creators as $creator) {
            $this->viewFactory->creator($view, $creator);
        }
    }

    /**
     * Apply the defined namespace to a class name
     *
     * @param array $classesOrFunctions The View Composer or Creator class
     * names to apply the namespace to. Ignores anonymous functions
     *
     * @return array The namespaced class names
     */
    protected function addNamespace(array $classesOrFunctions)
    {
        if (! strlen($this->namespace)) {
            return $classesOrFunctions;
        }

        foreach ($classesOrFunctions as $key => $classOrFunc) {
            if (is_callable($classOrFunc)) {
                continue;
            }

            $classesOrFunctions[$key] = $this->namespace . '\\' . $classOrFunc;
        }

        return $classesOrFunctions;
    }

    /**
     * Apply the defined prefix to view identifiers
     *
     * @param array $views The views to apply the prefix to
     *
     * @return array The prefixed views
     */
    protected function addPrefix(array $views)
    {
        if (! strlen($this->prefix)) {
            return $views;
        }

        foreach ($views as $key => $viewName) {
            $views[$key] = $this->prefix . '.' . $viewName;
        }

        return $views;
    }

    /**
     * Reset the views cache
     *
     * @return void
     */
    protected function resetViews()
    {
        $this->viewsToCompose = [];
        $this->viewsToCreate = [];
    }
}
