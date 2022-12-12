<?php

class CrowdhandlerDiagnostics
{

	/**
	 * @var array
	 */
    private $options;

    public function __construct()
	{
		if (function_exists('get_option')) {
			$this->options = get_option('crowdhandler_settings');
		}
	}

	/**
	 * Adds a crowdhandler header to the request
	 */
    public function addCHDiagnostics($headers)
	{
		if($this->options){
			$indexOverride = (isset($this->options['crowdhandler_settings_field_override_index'])) ? '1' : '0';
			$isEnabled = (isset($this->options['crowdhandler_settings_field_is_enabled'])) ? '1' : '0';
			$timestamp = new DateTime();
			$formatedTime = $timestamp->format(DateTime::ISO8601);
			$headers['x-crowdhandler-info'] = $this->options['crowdhandler_settings_field_public_key'] . '::' . $indexOverride . '::' . $isEnabled .'::' . $formatedTime;
		}
		return $headers;
	}
}