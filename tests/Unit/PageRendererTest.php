<?php

declare(strict_types=1);

/*
 * This file is part of the RenderEventBundle.
 *
 * (c) Runroom <runroom@runroom.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Runroom\RenderEventBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Runroom\RenderEventBundle\Event\PageRenderEvent;
use Runroom\RenderEventBundle\Renderer\PageRenderer;
use Runroom\RenderEventBundle\ViewModel\PageViewModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

class PageRendererTest extends TestCase
{
    protected $twig;
    protected $eventDispatcher;
    protected $pageViewModel;
    protected $service;

    protected function setUp(): void
    {
        $this->twig = $this->prophesize(Environment::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcherInterface::class);
        $this->pageViewModel = $this->prophesize(PageViewModel::class);

        $this->service = new PageRenderer(
            $this->twig->reveal(),
            $this->eventDispatcher->reveal(),
            $this->pageViewModel->reveal()
        );
    }

    /**
     * @test
     */
    public function itDispatchEventsOnRenderResponse()
    {
        $response = $this->prophesize(Response::class);

        $this->pageViewModel->setContent([])->shouldBeCalled();
        $this->twig->render('test.html.twig', Argument::type('array'), null)
            ->willReturn('Rendered test');
        $this->eventDispatcher->dispatch(Argument::type(PageRenderEvent::class), PageRenderEvent::EVENT_NAME)->willReturnArgument(0);
        $response->getContent()->willReturn('');
        $response->setContent('Rendered test')->willReturn($response->reveal());

        $resultResponse = $this->service->renderResponse('test.html.twig', [], $response->reveal());

        $this->assertSame($response->reveal(), $resultResponse);
    }
}
