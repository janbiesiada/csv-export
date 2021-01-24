<?php

namespace JanBiesiada\CsvExport\Test\Unit\Model;

use JanBiesiada\CsvExport\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @var ScopeConfigInterface|MockObject
     */
    private $scopeConfig;
    /**
     * @var Config
     */
    private $config;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->getMockForAbstractClass(ScopeConfigInterface::class);
        $this->config = new Config($this->scopeConfig);
    }

    public function testIsCsvExportEnabled()
    {
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(Config::CART_TO_CSV_EXPORT_GENERAL_ENABLED_PATH)
            ->willReturn(1);
        $this->assertEquals(true, $this->config->isCsvExportEnabled());
    }
    public function testIsCsvExportDisabled()
    {
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Config::CART_TO_CSV_EXPORT_GENERAL_ENABLED_PATH)
            ->willReturn(null);
        $this->assertEquals(false, $this->config->isCsvExportEnabled());
    }
}
