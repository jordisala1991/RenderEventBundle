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

namespace Runroom\RenderEventBundle\Tests\Integration;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Runroom\RenderEventBundle\DependencyInjection\RenderEventExtension;
use Runroom\RenderEventBundle\Renderer\PageRenderer;

class RenderEventExtensionTest extends AbstractExtensionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->load();
    }

    /**
     * @test
     */
    public function itHasCoreServicesAlias(): void
    {
        $this->assertContainerBuilderHasService(PageRenderer::class);
    }

    protected function getContainerExtensions(): array
    {
        return [new RenderEventExtension()];
    }
}
