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
			$formattedTime = $timestamp->format(DateTimeInterface::ATOM);
			$publicKey = isset($this->options['crowdhandler_settings_field_public_key'])
				? $this->options['crowdhandler_settings_field_public_key']
				: '';
			// PHP refuses to send any header containing CR/LF, so a stored key
			// with line breaks would drop this header entirely and emit a
			// warning. Strip them rather than rely on that being caught later.
			$publicKey = str_replace(array("\r", "\n"), '', $publicKey);
			$headers['x-crowdhandler-info'] = CROWDHANDLER_VERSION . '::' . $publicKey . '::' . $indexOverride . '::' . $isEnabled . '::' . $formattedTime;
		}
		return $headers;
	}
}