<?php

declare(strict_types=1);

namespace JanBiesiada\CsvExport\Model\Items;

use Magento\Quote\Api\Data\CartItemInterface;

class CsvFormatter
{
    /**
     * @var string[]
     */
    private $header;

    /**
     * CsvFormatter constructor.
     * @param string[] $header
     */
    public function __construct(
        array $header
    ) {
        $this->header = $header;
    }

    /**
     * @param CartItemInterface $item
     * @return array
     */
    public function getRow(CartItemInterface $item): array
    {
        return [
            $item->getItemId(),
            $item->getQty(),
            $item->getName(),
            $item->getPrice(),
            $item->getProductType(),
            $item->getSku()
        ];
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }
}
