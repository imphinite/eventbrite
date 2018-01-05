<?php 

namespace Eventbrite;

use Illuminate\Support\Facades\Log;

/**
 * Description of Eventbrite
 *
 * @author Yan Lin Wang <charles.w.developer@gmail.com>
 */

class WebService
{
    /*
    |--------------------------------------------------------------------------
    | Web Service Name
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    protected $name;

    /*
    |--------------------------------------------------------------------------
    | Web Service
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    protected $service;
    
    /*
    |--------------------------------------------------------------------------
    | API OAuth Token
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    protected $token;

    /*
    |--------------------------------------------------------------------------
    | API Endpoint
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    protected $endpoint;
    
    /*
    |--------------------------------------------------------------------------
    | Service URL
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    protected $requestUrl;
    
    /*
    |--------------------------------------------------------------------------
    | Verify SSL Peer
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    protected $verifySSL;

    /**
     * Class constructor
     */
    public function __construct()
    { 
        
    }
    
    /**
     * Set parameter by key.
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setParamByKey($key, $value)
    {
         if (array_key_exists($key, array_dot($this->service['param'])))
         {
             array_set($this->service['param'], $key, $value);
         }  
         
         return $this;
    }
    
    /**
     * Get parameter by the key.
     * @param string $key
     * @return mixed
     */ 
    public function getParamByKey($key)
    {
         if (array_key_exists($key, array_dot($this->service['param'])))
         {
             return array_get($this->service['param'], $key);
         }
    }
    
    /**
     * Set all parameters at once.
     * @param array $param
     * @return $this
     */
    public function setParam($param)
    {
        switch ($this->name)
        {
            case 'batchrequest':
                $this->service['param']['batch'] = json_encode($param);
                break;
            default:
                foreach (array_dot($param) as $key => $value)
                {
                    $this->setParamByKey($key, $value);
                }
                break;
        }

        return $this;
    }

    /**
     * Return parameters array.
     * @return array
     */
    public function getParam()
    {
        return $this->service['param'];
    }
    
    /**
     * Set target endpoint by key.
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setTargetEndpointByKey($key, $value)
    {
        $this->requestUrl = preg_replace('/\/:' . $key . '\//', '/' . $value . '/', $this->requestUrl);

        return $this;
    }

    /**
     * Set all targets in endpoint at once.
     * @param array $targets        Target name => ID of targets.
     * @return $this
     */
    public function setTargetEndpoint($targets)
    {
        foreach ($targets as $key => $value)
        {
            $this->setTargetEndpointByKey($key, $value);
        }

        return $this;
    }

    /**
     * 
     */
    public function setID($targets)
    {
        if (is_array($targets))
        {
            $this->setTargetEndpoint($targets);
        } else {
            $this->requestUrl = preg_replace('/\/:\w+\//', '/' . $targets . '/', $this->requestUrl);
        }

        return $this;
    }
    
    /**
     * Get Web Service Response.
     * @param string $needle        Response key.
     * @return string
     */
    public function get($needle = false)
    {
        return empty($needle)
                ? $this->getResponse()
                : $this->getResponseByKey($needle);
    }

    /**
     * Post JSON to Web Service.
     * @return type
     */
    public function post()
    {
        return $this->make(json_encode($this->service['param']));
    }
    
    /**
     * Get response value by key.
     * @param string $needle        Retrieves response parameter using "dot" notation.
     * @param int $offset 
     * @param int $length
     * @return array
     */
    public function getResponseByKey($needle = false, $offset = 0, $length = null)
    {
        // set default key parameter
        $needle = empty($needle) 
                    ? metaphone($this->service['responseDefaultKey']) 
                    : metaphone($needle);
        
        // get response
        $obj = json_decode($this->get(), true);
            
        // flatten array into single level array using 'dot' notation
        $obj_dot = array_dot($obj);
        // create empty response
        $response = [];
        // iterate 
        foreach ($obj_dot as $key => $val)
        {
            // Calculate the metaphone key and compare with needle
            if (strcmp(metaphone($key, strlen($needle)), $needle) === 0)
            {
                // set response value
                array_set($response, $key, $val);
            }
        }

        return count($response) < 1
               ? $obj
               : $response;
    }
    
    /**
     * Get response status.
     * @return mixed
     */
    public function getStatus()
    {
        // get response
        $obj = json_decode($this->get(), true);
        
        return array_get($obj, 'status', null);
    }

    /**
     * Make batch request param.
     * @return json Full request url.
     */
    public function getBatchJson()
    {
        $batch_json = [
            'method'            => $this->service['type'],
            'relative_url'      => str_replace(config('eventbrite.url'), '', $this->requestUrl)
        ];

        switch ($this->service['type'])
        {
            case 'POST':
                $batch_json['body'] = $body;
                break;
            case 'GET':
                $batch_json['relative_url'] .= $this->getBody(true);
                break;
            default:
                $batch_json['relative_url'] .= $this->getBody(true);
                break;
        }

        return json_decode(json_encode($batch_json));
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */     
   
    /**
     * Setup service parameters.
     */
    protected function build($service)
    {
        $this->validateConfig($service);

        $this->name = $service;
        
        // set web service parameters 
        $this->service = config('eventbrite.service.'.$service);
        
        // is API OAuth token set, use it, otherwise use default token
        $this->token = empty($this->service['token'])
                        ? config('eventbrite.token')
                        : $this->service['token'];
        
        // set service url
        $this->requestUrl = config('eventbrite.url') . $this->service['endpoint'];
        
        // is ssl_verify_peer key set, use it, otherwise use default key
        $this->verifySSL = empty(config('eventbrite.ssl_verify_peer')) 
                        ? FALSE
                        : config('eventbrite.ssl_verify_peer');

        $this->clearParameters();
    }
    
    /**
     * Validate configuration file.
     * @throws \ErrorException
     */ 
    protected function validateConfig($service)
    {
        // Check for config file
        if (!\Config::has('eventbrite'))
        {
            throw new \ErrorException('Unable to find config file.');
        }
        
        // Validate OAuth token parameter
        if (!array_key_exists('token', config('eventbrite')))
        {
            throw new \ErrorException('Unable to find OAuth token parameter in configuration file.');
        }

        // Validate API URL parameter
        if (!array_key_exists('url', config('eventbrite')))
        {
            throw new \ErrorException('Unable to find API URL parameter in configuration file.');
        }
        
        // Validate Service parameter
        if (!array_key_exists('service', config('eventbrite'))
                && !array_key_exists($service, config('eventbrite.service')))
        {
            throw new \ErrorException('Web service must be declared in the configuration file.');
        }
    }
    
    /**
     * Get parameter body of url.
     * @return string
     */
    protected function getBody($has_first_param=false)
    {
        $body = '';
        $is_first_param = false;
        if ($has_first_param)
        {
            $is_first_param = true;
        }

        if (empty($this->service['param']))
        {
            return $body;
        }
        
        foreach (array_dot($this->service['param']) as $key => $value)
        {
            if (is_null($value))
            {
                continue;
            }
            if ($is_first_param)
            {
                $body .= '?';
                $is_first_param = false;
            } else {
                $body .= '&';
            }
            $body .= $key . '=' . $value;
        }

        return $body;
    }

    /**
     * Get Web Service Response.
     * @return type
     */
    protected function getResponse()
    {
        $post = false;
        
        // set API OAuth token
        $this->requestUrl .= '?token=' . urlencode($this->token);
        switch($this->service['type'])
        {
            case 'POST':
                $post = json_encode($this->service['param']);
                break;
            case 'GET':
            default:
                $this->requestUrl .= $this->getBody();
                break;
        }
        
        return $this->make($post);
    }
    
    /**
     * Make cURL request to given URL.
     * @param boolean $isPost
     * @return object
     */
    protected function make($isPost = false)
    {
        $ch = curl_init($this->requestUrl);
       
        if ($isPost)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json;charset=UTF-8',
                'Content-Length: ' . strlen($isPost)
            ));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $isPost);
        }
       
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verifySSL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $output = curl_exec($ch);
       
        if ($output === false)
        {
            throw new \ErrorException(curl_error($ch));
        }

        curl_close($ch);
        return $output;
    }

    protected function clearParameters()
    {
        Parameters::resetParams();
    }
}
