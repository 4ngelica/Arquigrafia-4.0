<?php
namespace App\lib\metadata;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\lib\license\CreativeCommons_3_0;

class Exiv2 {

	private $log;
	private $photo;
	private $file_path;
  private $exif = [
    'Make' => "Image",
    'Model' => "Image",
    'Orientation' => "Image",
    'XResolution' => "Image",
    'YResolution' => "Image",
    'ResolutionUnit' => "Image",
    'Software' => "Image",
    'DateTime' => "Image",
    'YCbCrPositioning' => "Image",
    'THUMBNAIL' => "Thumbnail",
    'ExposureTime' => "Photo",
    'FNumber' => "Photo",
    'ExposureProgram' => "Photo",
    'ISOSpeedRatings' => "Photo",
    'ExifVersion' => "Photo",
    'DateTimeOriginal' => "Photo",
    'DateTimeDigitized' => "Photo",
    'ShutterSpeedValue' => "Photo",
    'ApertureValue' => "Photo",
    'BrightnessValue' => "Photo",
    'MeteringMode' => "Photo",
    'Flash' => "Photo",
    'FocalLength' => "Photo",
    'FlashpixVersion' => "Photo",
    'ColorSpace' => "Photo",
    'PixelXDimension' => "Photo",
    'PixelYDimension' => "Photo",
    'SensingMethod' => "Photo",
    'ExposureMode' => "Photo",
    'WhiteBalance' => "Photo",
    'FocalLengthIn35mmFilm' => "Photo",
    'SceneCaptureType' => "Photo"
  ];

	public function __construct($file_path, $photo, $metadata) {
    $authors_list = $photo->authors;
    $this->authors = "";
    $size = count($authors_list);
    $count = 0;
    foreach($authors_list as $author) {
      $count += 1;
      if($count < $size)
        $this->authors .= $author["name"] . "; ";
      else
        $this->authors .= $author["name"];
    }
    if($metadata == null)
      $metadata = [];
		$this->photo = $photo;
		$this->metadata = $metadata;
    $this->file_path = $file_path;
	}

	public function saveMetadata() {
    $photo = $this->photo;

		$this->setImageAuthor($this->authors);
		$this->setArtist($photo->imageAuthor, $this->authors);
		$this->setCopyRight($photo->imageAuthor,
			new CreativeCommons_3_0($photo->allowCommercialUses, $photo->allowModifications));
		$this->setDescription($photo->description);
    $this->setUserComment($photo->aditionalImageComments);
    $this->setGeneralMetadata($this->metadata, $this->exif);
	}

  public function setImageAuthor($authors) {
		$command = sprintf("Exif.Image.XPAuthor " . $this->toXP($authors));
		$this->runExif2($command);
	}

	public function setArtist($artist, $user) {
		$command = sprintf("Exif.Image.Artist %s - %s", $artist, $user);
		$this->runExif2($command);
	}

	public function setCopyRight($author, $ccl) {
		$command = sprintf("Exif.Image.Copyright %s - %s - %s", $author, $ccl->getLongLicenseName(), $ccl->getURIString());
		$this->runExif2($command);
		$command = sprintf("Iptc.Application2.Copyright %s - %s - %s", $author, $ccl->getLongLicenseName(), $ccl->getURIString());
		$this->runExif2($command);
	}

	public function setDescription($description) {
		$command = sprintf("Exif.Image.ImageDescription %s", $description);
		$this->runExif2($command);
	}

	public function setUserComment($userComment) {
		$command = sprintf("Exif.Photo.UserComment %s", $userComment);
		$this->runExif2($command);
  }

  public function setGeneralMetadata($metadata, $exif) {
    foreach($metadata as $metadata_tag => $metadata_value) {
      if(!array_key_exists($metadata_tag, $exif))
        continue;
      if(is_array($metadata_value)) {
        $keys = array_keys($metadata_value);
        if(is_string($keys[0])) {
          foreach($keys as $key) {
            $command = "Exif." . $this->exif[$metadata_tag] . "." . $key . " " .$metadata_value[$key];
            $this->runExif2($command);
          }
          continue;
        }
      }
      $command = "Exif." . $this->exif[$metadata_tag] . "." . $metadata_tag . " " .$metadata_value;
      $this->runExif2($command);
    }
  }

	private function toXP($string) {
		$bytes = unpack('c*', $string);
		$result = '';
		foreach($bytes as $b) {
			$result .= $b . ' 0 ';
		}
		$result .= '0 0';
		return $result;
	}

	private function runExif2($command) {
		$cmd = 'exiv2 -M "set ' . $command . '" ' . $this->file_path;
		system($cmd, $retval);
		if ($retval != 0) {
			$this->log_error($cmd, $retval);
		}
	}

	private function create_log() {
		$today = date('Y-m-d');
		$log = new Logger('Exiv2_logger');
		$file = storage_path() . '/logs/exiv2/' . $today . '.log';
		if (file_exists($file)) {
			$handle = fopen($file, 'a+');
			fclose($handle);
		}

		$log->pushHandler(new StreamHandler($file, Logger::ERROR));
		return $log;
	}

	private function log_error($cmd, $retval) {
		if (!isset($this->log)) $this->log = $this->create_log();
		$this->log->error("Houve um erro ao executar o seguinte comando:\n" . $cmd);
	}
}
