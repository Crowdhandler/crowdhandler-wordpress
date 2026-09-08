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
			$indexOverride = (isset($this->options['crowdhandler_settings_field_override_index'])) ? 'i' : '0';
			$isEnabled = (isset($this->options['crowdhandler_settings_field_is_enabled'])) ? 'e' : '0';
			$timestamp = new DateTime();
			$formatedTime = $timestamp->format(DateTimeInterface::ATOM);
			$publicKey = isset($this->options['crowdhandler_settings_field_public_key'])
				? $this->options['crowdhandler_settings_field_public_key']
				: '';
			$headers['x-crowdhandler-info'] = CROWDHANDLER_VERSION . '::' . $publicKey . '::' . $indexOverride . '::' . $isEnabled . '::' . $formatedTime;
		}
		return $headers;
	}
}