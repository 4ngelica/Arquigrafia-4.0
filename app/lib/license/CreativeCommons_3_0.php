<?php
namespace App\lib\license;

class CreativeCommons_3_0 {

	private $allowCommercialUses;
	private $allowModifications;

	public function __construct($allowCommercialUses, $allowModifications) {
		$this->allowModifications = $allowModifications;
		$this->allowCommercialUses = $allowCommercialUses;
	}

	public function getLongLicenseName() {
		return 'Creative Commons 3.0 ' . $this->getShortLicenseName();
	}

	public function getShortLicenseName() {
		$result = 'BY';
		switch ($this->allowCommercialUses) {
			case 'YES':
				// Nothing
				break;
			case 'NO':
				$result .= '-NC';
				break;
		}
		switch ($this->allowModifications) {
			case 'YES':
				// Nothing
				break;
			case 'YES_SA':
				$result .= '-SA';
				break;
			case 'NO':
				$result .= '-ND';
				break;
		}
		return $result;
	}

	public function getURIString() {
		return sprintf('http://creativecommons.org/licenses/%s/3.0/deed.pt_BR', strtolower($this->getShortLicenseName()));
	}

}
