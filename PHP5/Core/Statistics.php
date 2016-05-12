<?php

namespace CryptoTraderHub\Core;

class Statistics {
	
	// Calculate the standard deviation of the suplpied array
    public static function standardDeviation($values_arr){
		$mean = array_sum($values_arr) / count($values_arr);
	    $variance = 0.0;
	    foreach ($values_arr as $i){
	        $variance += pow($i - $mean, 2);
	    }
	    $variance /= count($values_arr);
	    return (float) sqrt($variance);
    }
	
	public static function mean($values_arr){
		return (float) (array_sum($values_arr) / count($values_arr));
    }
	
}