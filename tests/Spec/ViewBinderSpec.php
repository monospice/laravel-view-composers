<?php

namespace Spec\Monospice\LaravelViewComposers;

use Mockery;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ViewBinderSpec extends ObjectBehavior
{
    /**
     * The Laravel View Factory mock
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $viewFactory;

    function let()
    {
        $this->viewFactory = Mockery::mock('Illuminate\Contracts\View\Factory');
        $this->beConstructedWith($this->viewFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Monospice\LaravelViewComposers\ViewBinder');
    }

    function it_binds_a_view_to_a_view_composer()
    {
        $this->viewFactory->shouldReceive('composer')
            ->with(['testview'], 'TestComposer')->once();

        $this->compose('testview')->with('TestComposer')
            ->shouldReturn($this);
    }

    function it_binds_multiple_views_to_a_view_composer()
    {
        $this->viewFactory->shouldReceive('composer')
            ->with(['view1', 'view2'], 'TestComposer')->once();

        $this->compose('view1', 'view2')->with('TestComposer')
            ->shouldReturn($this);
    }

    function it_binds_a_view_to_multiple_view_composers()
    {
        $this->viewFactory->shouldReceive('composer')
            ->with(['testview'], 'TestComposer')->once()
            ->shouldReceive('composer')
            ->with(['testview'], 'AnotherComposer')->once();

        $this->compose('testview')->with('TestComposer', 'AnotherComposer')
            ->shouldReturn($this);
    }

    function it_binds_a_view_to_a_view_creator()
    {
        $this->viewFactory->shouldReceive('creator')
            ->with(['testview'], 'TestCreator')->once();

        $this->create('testview')->with('TestCreator')
            ->shouldReturn($this);
    }

    function it_binds_multiple_views_to_a_view_creator()
    {
        $this->viewFactory->shouldReceive('creator')
            ->with(['view1', 'view2'], 'TestCreator')->once();

        $this->create('view1', 'view2')->with('TestCreator')
            ->shouldReturn($this);
    }

    function it_binds_a_view_to_multiple_view_creators()
    {
        $this->viewFactory->shouldReceive('creator')
            ->with(['testview'], 'TestCreator')->once()
            ->shouldReceive('creator')
            ->with(['testview'], 'AnotherCreator')->once();

        $this->create('testview')->with('TestCreator', 'AnotherCreator')
            ->shouldReturn($this);
    }

    function it_sets_the_namespace_for_bound_classes()
    {
        $this->viewFactory->shouldReceive('composer')
            ->with(['testview'], 'Test\TestComposer')->once()
            ->shouldReceive('composer')
            ->with(['testview'], 'Test\AnotherComposer')->once();

        $this->setNamespace('Test')
            ->compose('testview')->with('TestComposer', 'AnotherComposer')
            ->shouldReturn($this);
    }

    function it_sets_the_prefix_for_bound_views()
    {
        $this->viewFactory->shouldReceive('composer')
            ->with(['test.view1', 'test.view2'], 'TestComposer')->once();

        $this->setPrefix('test')
            ->compose('view1', 'view2')->with('TestComposer')
            ->shouldReturn($this);
    }

    function it_preserves_the_namespace_and_prefix_for_subsequent_bindings()
    {
        $this->viewFactory->shouldReceive('composer')
            ->with(['test.view1'], 'Test\TestComposer')->once()
            ->shouldReceive('composer')
            ->with(['test.view2'], 'Test\AnotherComposer')->once();

        $this->setNamespace('Test')->setPrefix('test')
            ->compose('view1')->with('TestComposer')
            ->shouldReturn($this);

        $this->compose('view2')->with('AnotherComposer')
            ->shouldReturn($this);
    }
}
