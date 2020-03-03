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

namespace Runroom\RenderEventBundle\Renderer;

use Runroom\RenderEventBundle\Event\PageRenderEvent;
use Runroom\RenderEventBundle\ViewModel\PageViewModelInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PageRenderer
{
    protected $twig;
    protected $eventDispatcher;
    protected $pageViewModel;

    public function __construct(
        Environment $twig,
        EventDispatcherInterface $eventDispatcher,
        PageViewModelInterface $pageViewModel
    ) {
        $this->twig = $twig;
        $this->eventDispatcher = $eventDispatcher;
        $this->pageViewModel = $pageViewModel;
    }

    public function renderResponse(string $view, $model = null, Response $response = null): Response
    {
        $this->pageViewModel->setContent($model);
        $event = new PageRenderEvent($view, $this->pageViewModel, $response ?? new Response());

        $this->eventDispatcher->dispatch($event, PageRenderEvent::EVENT_NAME);

        $response = $event->getResponse();
        if ($response instanceof RedirectResponse || !empty($response->getContent())) {
            return $response;
        }

        return $response->setContent($this->twig->render(
            $event->getView(),
            ['page' => $event->getPageViewModel()]
        ));
    }
}