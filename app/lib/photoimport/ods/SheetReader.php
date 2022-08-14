<?php namespace lib\photoimport\ods;

use Excel;

class SheetReader {

  protected $mapper;

  public function __construct(ColumnMapper $cm) {
    $this->mapper = $cm;
  }

  public function load($file) {
    return Excel::selectSheetsByIndex(0)->load($file);
  }

  public function getSheet($document) {
    $document->formatDates(true, 'Y-m-d');
    return $document->get();
  }

  public function readRow($row) {
    return $this->mapper->transform($row->all());
  }

  public function read($file) {
    $document = $this->load($file);
    $sheet = $this->getSheet($document);
    $rows = array();
    foreach ($sheet as $row) {
      $rows[] = $this->readRow($row);
    }
    return $rows;
  }

}