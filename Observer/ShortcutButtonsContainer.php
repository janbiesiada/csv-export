<?php

declare(strict_types=1);

namespace JanBiesiada\CsvExport\Observer;

use JanBiesiada\CsvExport\Block\MiniCart\Button;
use JanBiesiada\CsvExport\Model\Config;
use Magento\Catalog\Block\ShortcutButtons;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ShortcutButtonsContainer implements ObserverInterface
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if (!$this->config->isCsvExportEnabled()) {
            return ;
        }
        /** @var ShortcutButtons $container */
        $shortcutButtons = $observer->getEvent()->getContainer();
        /** @var Button $shortcut */
        $shortcut = $shortcutButtons->getLayout()->createBlock(
            Button::class,
            '',
            []
        );
        $shortcut->setTemplate('JanBiesiada_CsvExport::csv-cart-export-button.phtml');
        $shortcutButtons->addShortcut($shortcut);
    }
}
