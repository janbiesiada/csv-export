<?php

declare(strict_types=1);

namespace JanBiesiada\CsvExport\Controller\Cart;

use Exception;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Quote\Api\Data\CartItemInterface;

class Export implements HttpGetActionInterface
{
    private const EXPORT_PATH = 'export';
    private const TEMP_FILE_NAME = 'export/custom_%s.csv';
    private const CART_CSV_FILE_NAME = 'cart.csv';
    const CART_TO_CSV_EXPORT_GENERAL_ENABLED_PATH = 'cart_to_csv_export/general/enabled';
    /**
     * @var FileFactory
     */
    private $fileFactory;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Session
     */
    private $checkoutSession;
    /**
     * @var string
     */
    private $filePath;
    /**
     * @var ScopeConfigInterface
     */
    private $config;
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $redirectFactory;

    public function __construct(
        FileFactory $fileFactory,
        Filesystem $filesystem,
        Session $checkoutSession,
        ScopeConfigInterface $config,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
    ) {
        $this->fileFactory = $fileFactory;
        $this->filesystem = $filesystem;
        $this->checkoutSession = $checkoutSession;
        $this->config = $config;
        $this->redirectFactory = $redirectFactory;
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
        if (!$this->config->getValue(self::CART_TO_CSV_EXPORT_GENERAL_ENABLED_PATH)) {
            return $this->redirectFactory->create()->setPath('/');
        }
        $directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $directory->create(self::EXPORT_PATH);
        $stream = $directory->openFile($this->getFilePath(), 'w+');
        $stream->lock();
        $stream->writeCsv(
            $this->getHeader()
        );
        foreach ($this->checkoutSession->getQuote()->getItems() as $item) {
            $stream->writeCsv($this->formatAsRow($item));
        }

        return $this->fileFactory->create(
            self::CART_CSV_FILE_NAME,
            $this->getContent(),
            DirectoryList::VAR_DIR
        );
    }

    /**
     * @return string[]
     */
    private function getHeader(): array
    {
        return ['item_id', 'qty', 'name', 'price', 'product_type', 'sku'];
    }

    /**
     * @param CartItemInterface $item
     * @return array
     */
    private function formatAsRow(CartItemInterface $item): array
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
     * @return string[]
     */
    private function getContent(): array
    {
        return [
            'type' => 'filename',
            'value' => $this->getFilePath(),
            'rm' => '1'
        ];
    }

    /**
     * @return string
     */
    private function getFilePath(): string
    {
        if (!$this->filePath) {
            $this->filePath = sprintf(self::TEMP_FILE_NAME, date('m_d_Y_H_i_s'));
        }
        return $this->filePath;
    }
}
