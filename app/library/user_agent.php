<?php if ( ! defined('_ROOT_')) exit('No direct script access allowed');

class User_Agent {
	public $agent		= NULL;
	
	public $is_browser	= FALSE;
	public $is_robot	= FALSE;
	
	public $platform	= '';
	public $browser	= '';
	public $version	= '';
	
	public $countryName	= 'Unknown';
	public $countryCode = 'XX';
	public $cityName = 'Unknown';
	
	function User_Agent()
	{
		$this->agent = trim($_SERVER['HTTP_USER_AGENT']);
		
		// Load the user agent info details
		$this->load_agent_file();
	}
	
	private function load_agent_file()
	{
		include(_ROOT_.'/app/config/user_agents.php');
		
		$this->platform = 'Unknown Platform';
		
		// Parse and load the platform/OS
		if (is_array($platforms) AND count($platforms) > 0)
		{
			foreach ($platforms as $key => $val)
			{
				if (preg_match("|".preg_quote($key)."|i", $this->agent))
				{
					$this->platform = $val;
					break;
				}
			}
		}
			
		// Parse the browser name and Version
		if (is_array($browsers) AND count($browsers) > 0)
		{
			foreach ($browsers as $key => $val)
			{		
				if (preg_match("|".preg_quote($key).".*?([0-9\.]+)|i", $this->agent, $match))
				{
					$this->is_browser = TRUE;
					$this->version = $match[1];
					$this->browser = $val;
					
					break;
				}
			}
		}
		
		// Get the user location info
		$visitorGeolocation = $this->getGeoLocation();
		
		if ($visitorGeolocation != null && $visitorGeolocation['statusCode'] == 'OK')
		{	
			$this->countryName = $visitorGeolocation['countryName'];
			$this->countryCode = $visitorGeolocation['countryCode'];
			$this->cityName = $visitorGeolocation['cityName'];
		}
	}
	
	// Find the Country Name, Code and City information from the IPInfoDB:
	// http://www.ipinfodb.com/ip_location_api_json.php
	private function getGeoLocation()
	{
		// Make a special cookie name by adding the IP address (to make different cookie per IP address)
		$cookieName = 'geolocation_'. str_replace('.', '_', $_SERVER['REMOTE_ADDR']);
		
		//Check if geolocation cookie exists (to avoid repeated calls to their server)
		if(!isset($_COOKIE[$cookieName]))
		{
			// If no, create a new request;
			$APIKey='427c53fe71d442672c3d2ceeea67622016242616e2f14292356728e16bfd343d';
			$JSONUrl='http://api.ipinfodb.com/v3/ip-city/?key='.$APIKey.'&ip='.$_SERVER['REMOTE_ADDR'].'&format=json';
			
			// Get the JSON content from the remote URL
			$response = file_get_contents($JSONUrl);
			
			// Parse JSON
			$visitorGeolocation  = json_decode($response, true);
			
			// Geo Location query was successfull
			if ($visitorGeolocation && $visitorGeolocation['statusCode'] == 'OK') {
				$data = base64_encode(serialize($visitorGeolocation));
				setcookie($cookieName, $data, time()+3600*24*7); //set cookie for 1 week
			}
		}
		else
		{
			// Load previously stored info from the cooki
			$visitorGeolocation = unserialize(base64_decode($_COOKIE[$cookieName]));
		}
		
		return $visitorGeolocation;
	}
}
				
?>