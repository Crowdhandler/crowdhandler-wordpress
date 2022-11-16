<?php

use CrowdHandler\GateKeeper;
use CrowdHandler\PublicClient;

class CrowdHandlerGateKeeper
{
	/**
	 * @var CrowdHandlerWordPressGateKeeper
	 */
	private $gateKeeper;

	/**
	 * @var array
	 */
	private $options;

	/**
	 * @var bool
	 */
	private $requestChecked = false;

	/**
	 * @var bool
	 */
	private $requestPerformanceRecorded = false;

	/**
	 * @param array $options
	 */
	public function __construct($options = null)
	{
		if (is_array($options)) {
			$this->options = $options;
		} elseif (function_exists('get_option')) {
			$this->options = get_option('crowdhandler_settings');
		}
	}

	public function checkRequest()
	{
		if ((function_exists('is_admin') && is_admin()) || !$this->isEnabled() || $this->requestChecked) {
			return $this;
		}

		$api = new PublicClient($this->options['crowdhandler_settings_field_public_key']);
		$this->gateKeeper = new GateKeeper($api);
		
		$this->gateKeeper->setIgnoreUrls(
			"/^((?!.*\?).*(\.(avi|css|eot|gif|ico|jpg|jpeg|js|json|mov|mp4|mpeg|mpg|og[g|v]|pdf|png|svg|ttf|txt|wmv|woff|woff2|xml))$)|(?!.*\?.*w[c|p]-.+).*(^.*w[c|p]-.+)|^((?!.*\?.*xmlrpc\.php).*xmlrpc.php)|\?rest_route=.+/"
		);

		$isHostServer = $this->gateKeeper->ip === $_SERVER["SERVER_ADDR"];

		if (!$isHostServer) {
			$this->gateKeeper->setFailTrust(true);
			$this->gateKeeper->checkRequest();
			$this->gateKeeper->redirectIfNotPromoted();
		}


		$this->requestChecked = true;

		return $this;
	}

	public function recordPerformance($code)
	{
		if ((function_exists('is_admin') && is_admin()) || !$this->isEnabled() || $this->requestPerformanceRecorded) {
			return false;
		}

		if (!$this->gateKeeper instanceof GateKeeper) {
			return false;
		}

		try {
			$this->requestPerformanceRecorded = true;
			$this->gateKeeper->recordPerformance($code);
		} catch (\Exception $e) {
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return
			isset($this->options['crowdhandler_settings_field_is_enabled']) &&
			$this->options['crowdhandler_settings_field_is_enabled'] == 'on' &&
			isset($this->options['crowdhandler_settings_field_public_key']) &&
			$this->options['crowdhandler_settings_field_public_key']
		;
	}

}
