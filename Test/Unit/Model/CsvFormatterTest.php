<?php

declare(strict_types=1);

namespace JanBiesiada\CsvExport\Test\Unit\Model;

use JanBiesiada\CsvExport\Model\Items\CsvFormatter;
use Magento\Quote\Api\Data\CartItemInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CsvFormatterTest extends TestCase
{
    private const HEADER = ['item_id', 'qty', 'name', 'price', 'product_type', 'sku'];
    /**
     * @var CsvFormatter
     */
    private $csvFormatter;
    /**
     * @var CartItemInterface|MockObject
     */
    private $item;

    public function testGetHeader()
    {
        $this->assertEquals(self::HEADER, $this->csvFormatter->getHeader());
    }

    /**
     * @param string $itemId
     * @param string $qty
     * @param string $name
     * @param string $price
     * @param string $productType
     * @param string $sku
     * @testWith ["1", "100", "Test Product 1", "10", "simple", "test-sku-1"]
     *           ["2", "200", "Test Product 2", "20", "simple", "test-sku-2"]
     *           ["3", "300", "Test Product 3", "30", "configurable", "test-sku-3"]
     *           ["4", "400", "Test Product 4", "40", "simple", "test-sku-4"]
     *           ["5", "500", "Test Product 5", "50", "simple", "test-sku-5"]
     */
    public function testGetRow(
        string $itemId,
        string $qty,
        string $name,
        string $price,
        string $productType,
        string $sku
    ) {

        $this->item->expects($this->once())
            ->method('getItemId')
            ->willReturn($itemId);
        $this->item->expects($this->once())
            ->method('getQty')
            ->willReturn($qty);
        $this->item->expects($this->once())
            ->method('getName')
            ->willReturn($name);
        $this->item->expects($this->once())
            ->method('getPrice')
            ->willReturn($price);
        $this->item->expects($this->once())
            ->method('getProductType')
            ->willReturn($productType);
        $this->item->expects($this->once())
            ->method('getSku')
            ->willReturn($sku);
        $this->assertEquals(
            [$itemId, $qty, $name, $price, $productType, $sku],
            $this->csvFormatter->getRow($this->item)
        );
    }

    protected function setUp(): void
    {
        $this->item = $this->getMockForAbstractClass(CartItemInterface::class);
        $this->csvFormatter = new CsvFormatter(self::HEADER);
    }
}
