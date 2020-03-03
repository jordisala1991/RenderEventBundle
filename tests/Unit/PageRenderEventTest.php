<?php

namespace Runroom\RenderEventBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Runroom\RenderEventBundle\Event\PageRenderEvent;
use Runroom\RenderEventBundle\ViewModel\PageViewModel;
use Symfony\Component\HttpFoundation\Response;

class PageRenderEventTest extends TestCase
{
    protected $pageViewModel;
    protected $response;
    protected $pageRenderEvent;

    protected function setUp(): void
    {
        $this->pageViewModel = $this->prophesize(PageViewModel::class);
        $this->response = $this->prophesize(Response::class);

        $this->pageRenderEvent = new PageRenderEvent(
            'view',
            $this->pageViewModel->reveal(),
            $this->response->reveal()
        );
    }

    /**
     * @test
     */
    public function itSetsPageViewModel()
    {
        $expectedViewModel = new PageViewModel();

        $this->pageRenderEvent->setPageViewModel($expectedViewModel);

        $viewModel = $this->pageRenderEvent->getPageViewModel();

        $this->assertSame($expectedViewModel, $viewModel);
    }

    /**
     * @test
     */
    public function itGetsPageViewModel()
    {
        $this->pageViewModel->getContent()->willReturn('model');

        $viewModel = $this->pageRenderEvent->getPageViewModel();

        $this->assertInstanceOf(PageViewModel::class, $viewModel);
        $this->assertSame('model', $viewModel->getContent());
    }
}