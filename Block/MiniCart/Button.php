<?php

declare(strict_types=1);

namespace JanBiesiada\CsvExport\Block\MiniCart;

use Magento\Catalog\Block\ShortcutInterface;
use Magento\Framework\View\Element\Template;

class Button extends Template implements ShortcutInterface
{
    public function getAlias(): string
    {
        return $this->getData('alias') ?? 'csv.cart.export.button.button';
    }
}
