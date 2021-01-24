<?php

declare(strict_types=1);

namespace JanBiesiada\CsvExport\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\File\WriteInterface;

class FileGenerator
{
    private const EXPORT_PATH = 'export';
    private const TEMP_FILE_NAME = 'export/custom_%s.csv';
    /**
     * @var string
     */
    private $filePath;
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(
        Filesystem $filesystem
    ) {
        $this->filesystem = $filesystem;
    }

    /**
     * @return WriteInterface
     * @throws FileSystemException
     */
    public function getFile(): WriteInterface
    {
        $directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $directory->create(self::EXPORT_PATH);
        $stream = $directory->openFile($this->getFilePath(), 'w+');
        $stream->lock();
        return $stream;
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

    /**
     * @return string[]
     */
    public function getContent(): array
    {
        return [
            'type' => 'filename',
            'value' => $this->getFilePath(),
            'rm' => '1'
        ];
    }
}
