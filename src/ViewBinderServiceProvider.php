<?php

namespace Monospice\LaravelViewComposers;

use Illuminate\Support\ServiceProvider;
use Monospice\SpicyIdentifiers\DynamicMethod;

use Monospice\LaravelViewComposers\ComposerBinder;

/**
 * Binds View Composers and View Creators to views
 *
 * @category Package
 * @package  Monospice\LaravelViewComposers
 * @author   Cy Rossignol <cy@rossignols.me>
 * @license  See LICENSE file
 * @link     https://github.com/monospice/laravel-view-composers
 */
class ViewBinderServiceProvider extends ServiceProvider
{
    /**
     * Bind the View Composers to the Views during service boot
     *
     * @return void
     */
    public function boot()
    {
        $this->viewBinder = new ViewBinder($this->app['view']);

        $this->callViewBindingMethods();
    }

    /**
     * Register the service
     *
     * @return void
     */
    public function register()
    {
        // nothing to do
    }

    /**
     * Calls each of the view binding methods
     *
     * @return void
     */
    protected function callViewBindingMethods()
    {
        $classMethods = get_class_methods($this);

        foreach ($classMethods as $methodName) {
            $method = DynamicMethod::parse($methodName);

            if ($method->first() === 'bind' && $method->last() === 'Views') {
                $this->viewBinder->setNamespace('')->setPrefix('');
                $method->invokeOn($this);
            }
        }
    }

    /**
     * Pass view binding methods to the ViewBinder instance
     *
     * @param string $methodName The called method name
     * @param array $arguments The called method arguments
     *
     * @return mixed The return value from the ViewBinder method
     *
     * @throws \BadMethodCallException If the method does not exist in the
     * ViewBinder instance
     */
    public function __call($methodName, $arguments)
    {
        $method = DynamicMethod::load($methodName);

        if ($method->existsOn($this->viewBinder)) {
            return $method->callOn($this->viewBinder, $arguments);
        }

        return parent::__call($methodName, $arguments);
    }
}
