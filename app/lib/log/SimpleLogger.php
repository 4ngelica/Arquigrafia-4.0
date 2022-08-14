<?php namespace lib\log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use File;

class SimpleLogger {

  protected $logger;

  protected $root;

  protected $levels = [
    'addInfo',
    'addNotice',
    'addWarning',
    'addError',
  ];

  public function init($logger_name, $log_folder, $in_storage = true) {
    $this->logger = $this->newLogger($logger_name);
    $this->setRoot($log_folder, $in_storage);
    $this->createRootFolder();
  }

  public function newLogger($logger_name) {
    return new Logger($logger_name);
  }

  public function setRoot($log_folder, $in_storage) {
    if ( $in_storage ) {
      $this->root = storage_path() . '/logs/' . $log_folder . '/';
    } else {
      $this->root = $log_folder . '/';
    }
  }

  public function getRoot() {
    return $this->root;
  }

  public function createRootFolder() {
    if ( ! File::exists($this->root) ) {
      File::makeDirectory($this->root);
    }
  }

  public function createFileLog($file) {
    if ( ! File::exists($file) ) {
      File::put($file, '');
    }
  }

  public function getFilePath($file) {
    return $this->root . $file . '.log';
  }

  public function logToFile($file_name) {
    $file_path = $this->getFilePath($file_name);
    $this->createFileLog($file_path);
    $handler = $this->newHandler($file_path);
    $this->logger->pushHandler($handler);
  }

  public function newHandler($file) {
    $handler = new StreamHandler($file);
    $handler->setFormatter($this->newFormatter());
    return $handler;
  }

  public function newFormatter() {
    return new LineFormatter(null, null, false, true);
  }

  public function addInfo($message, array $context = array())
  {
    return $this->logger->addInfo($message, $context);
  }

  public function addNotice($message, array $context = array())
  {
    return $this->logger->addNotice($message, $context);
  }

  public function addWarning($message, array $context = array())
  {
    return $this->logger->addWarning($message, $context);
  }

  public function addError($message, array $context = array())
  {
    return $this->logger->addError($message, $context);
  }

}