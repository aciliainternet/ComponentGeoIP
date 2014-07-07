<?php
namespace Acilia\Component\GeoIP\Service;

use Exception;

class GeoIPService
{
	private $cache;

	public function __construct($debug = false)
	{
		$this->cache = [];

		if (!function_exists('geoip_country_code_by_name')) {
            if ($debug == true) {
            	throw new Exception('Function "geoip_country_code_by_name" is not defined. Please verify that PHP GeoIP Module is enabled!');

            } else {
            	// O1 is the code for Other Country -- http://dev.maxmind.com/geoip/legacy/codes/iso3166/
                function geoip_country_code_by_name($hostname) {
                    return 'O1';
                }
            }
		}
	}

    public function getCountryCode($ipAddress)
    {
    	$ipAddressHash = sha1($ipAddress);

    	if (!isset($this->cache[$ipAddressHash])) {
    		$countryCode = @geoip_country_code_by_name($ipAddress);
    		if ($countryCode === false) {
    			$countryCode = 'O1';
    		}

    		$this->cache[$ipAddressHash] = strtoupper($countryCode);
    	}

    	return $this->cache[$ipAddressHash];
    }
}