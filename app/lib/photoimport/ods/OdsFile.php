<?php namespace lib\photoimport\ods;

class OdsFile {

  protected $file;

  protected $sheet;

  protected $logger;

  public function __construct(\SplFileInfo $file, OdsFileLogger $logger = null) {
    $this->file = $file;
    $this->logger = $logger ?: new OdsFileLogger;
    $filename = $this->getFilename();
    $this->logger->init($filename . '_logger', $this->getBasePath(), false);
    $this->logger->logToFile($filename);
  }

  public function getBasePath() {
    return $this->file->getPath();
  }

  public function getFilename() {
    return $this->file->getFilename();
  }

  public function getPathname() {
    return $this->file->getPathname();
  }

  public function logError($message) {
    $this->logger->addError($message);
  }

  public function logInfo($message) {
    $this->logger->addInfo($message);
  }

}