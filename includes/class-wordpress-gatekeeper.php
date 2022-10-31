<?php

use CrowdHandler\GateKeeper;
use CrowdHandler\Client;
use CrowdHandler\Timer;

class CrowdHandlerWordPressGateKeeper extends GateKeeper
{

    const CROWDHANDLER_PARAMS = array(
        'ch-id',
        'ch-fresh',
        'ch-id-signature',
        'ch-public-key',
        'ch-requested',
        'ch-code'
    );

    const HTTP_REDIRECT_CODE = 302;

    public function __construct(Client $client, \Psr\Http\Message\ServerRequestInterface $request=null) 
    {   

        parent::__construct($client, $request=null);

        if($request) {
            $get_params = $request->getQueryParams();
        } else {
            $get_params = $_GET;
        }

        $this->sanitizePromotedRedirect($this->url, $get_params);

    }

    /**
     * Removes crowdhandler specific query parameters on promotion
     * @param string $url The url that is currently being requested
     * @param array $get An array of the current query sring parameters   
     */
    private function sanitizePromotedRedirect ($url, $get)
    {
        
        if ($get[parent::TOKEN_URL]) {

            $parsed_url  = parse_url($url);
            $filtered_url = "https://" . $parsed_url["host"] . $parsed_url["path"];
            
            // create an array of crowdhandler query paramaters to remove
            $ch_params_to_remove = array();
            for ($i=0; $i < Count(self::CROWDHANDLER_PARAMS); $i++) {
                if ($get[self::CROWDHANDLER_PARAMS[$i]] || $get[self::CROWDHANDLER_PARAMS[$i]]== "")
                {
                    array_push($ch_params_to_remove, $get[self::CROWDHANDLER_PARAMS[$i]]);
                }
            }

            // create an array without the crowdhandler parameters
            $existing_query_parameters = array_diff($get, $ch_params_to_remove);

            // if we have any existing query paramters then re-add them to the url
            if (Count($existing_query_parameters) > 0) {
                $redirectUrl = $filtered_url .= '?' . http_build_query($existing_query_parameters);
            } else {
                $redirectUrl = $filtered_url;
            }

            // and redirect
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header('location: '.$redirectUrl, true, self::HTTP_REDIRECT_CODE);
            exit;
        }
    }
   


    
}