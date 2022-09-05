<?php
namespace App\lib\utils;

class HelpTool {

	public static function formattingLongText($firstText, $alternativeText, $limitNumber)
	{ 	$resulString = null;
		if ($firstText != null) {
    		if(strlen($firstText) > $limitNumber){
    			if($alternativeText != "" || $alternativeText != null){
    				$resulString = $alternativeText;
    			}else{
    				//$resulString = Str::limit(ucfirst($firstText), $limitNumber);
    				$resulString = str_limit(ucfirst($firstText), $limitNumber, $end = '...');
    			}
    		}else{
    			$resulString = $firstText;
    		}
    	}
    	return $resulString;
	}



}
