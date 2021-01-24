<?php

namespace JanBiesiada\CsvExport\Test\Unit\Block\MiniCart;

use JanBiesiada\CsvExport\Block\MiniCart\Button;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
{
    /**
     * @var Button
     */
    private $button;

    protected function setUp(): void
    {
        /** @var Context $context */
        $context = $this->createMock(Context::class);
        $this->button = new Button($context, []);
        parent::setUp();
    }

    public function testGetAlias()
    {
        $this->assertEquals('csv.cart.export.button.button', $this->button->getAlias());
        $this->button->setData('alias', 'test');
        $this->assertEquals('test', $this->button->getAlias());
    }
}
