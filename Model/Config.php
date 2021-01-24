<?php

declare(strict_types=1);

namespace JanBiesiada\CsvExport\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    private const CART_TO_CSV_EXPORT_GENERAL_ENABLED_PATH = 'cart_to_csv_export/general/enabled';
    /**
     * @var ScopeConfigInterface
     */
    private $config;

    public function __construct(
        ScopeConfigInterface $config
    ) {
        $this->config = $config;
    }

    public function isCsvExportEnabled(): bool
    {
        return $this->config->getValue(self::CART_TO_CSV_EXPORT_GENERAL_ENABLED_PATH) ? true : false;
    }
}
