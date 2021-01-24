<?php

declare(strict_types=1);

namespace JanBiesiada\CsvExport\Controller\Cart;

use Exception;
use JanBiesiada\CsvExport\Model\Config;
use JanBiesiada\CsvExport\Model\FileGenerator;
use JanBiesiada\CsvExport\Model\Items\CsvFormatterFactory;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;

class Export implements HttpGetActionInterface
{
    private const CART_CSV_FILE_NAME = 'cart.csv';
    /**
     * @var FileFactory
     */
    private $fileFactory;
    /**
     * @var Session
     */
    private $checkoutSession;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var RedirectFactory
     */
    private $redirectFactory;
    /**
     * @var CsvFormatterFactory
     */
    private $csvFormatterFactory;
    /**
     * @var FileGenerator
     */
    private $fileGenerator;

    public function __construct(
        FileFactory $fileFactory,
        Session $checkoutSession,
        Config $config,
        RedirectFactory $redirectFactory,
        CsvFormatterFactory $csvFormatterFactory,
        FileGenerator $fileGenerator
    ) {
        $this->fileFactory = $fileFactory;
        $this->checkoutSession = $checkoutSession;
        $this->config = $config;
        $this->redirectFactory = $redirectFactory;
        $this->csvFormatterFactory = $csvFormatterFactory;
        $this->fileGenerator = $fileGenerator;
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws FileSystemException
     * @throws Exception
     */
    public function execute()
    {
        if (!$this->config->isCsvExportEnabled()) {
            return $this->redirectFactory->create()->setPath('/');
        }
        $tempFile = $this->fileGenerator->getFile();

        $formatter = $this->csvFormatterFactory->create([
            'header' => ['item_id', 'qty', 'name', 'price', 'product_type', 'sku']
        ]);
        $tempFile->writeCsv(
            $formatter->getHeader()
        );
        foreach ($this->checkoutSession->getQuote()->getItems() as $item) {
            $tempFile->writeCsv($formatter->getRow($item));
        }
        return $this->fileFactory->create(
            self::CART_CSV_FILE_NAME,
            $this->fileGenerator->getContent(),
            DirectoryList::VAR_DIR
        );
    }
}
