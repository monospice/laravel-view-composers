<?php

namespace Spec\Monospice\LaravelViewComposers;

use Mockery;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ViewBinderServiceProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType(
            'Monospice\LaravelViewComposers\ViewBinderServiceProvider'
        );
    }

    function it_binds_views_to_view_composers_and_creators()
    {
        $viewFactory = Mockery::mock('Illuminate\Contracts\View\Factory');
        $viewFactory->shouldReceive('composer')
            ->with(['testview'], 'TestComposer')->once();

        $app = ['view' => $viewFactory];
        $this->beConstructedWith($app);

        $this->boot();
    }

    /**
     * Stub to test the binding of views by this service provider
     *
     * @return void
     */
    protected function bindStubViews()
    {
        $this->compose('testview')->with('TestComposer');
    }
}
