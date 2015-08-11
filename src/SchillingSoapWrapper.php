<?php namespace LasseLehtinen\SchillingSoapWrapper;

use SoapClient;
use DOMDocument;

class SchillingSoapWrapper
{
    private $client;
    private $hostname;
    private $port;
    private $username;
    private $password;
    private $company;

    public function __construct($hostname, $port, $username, $password, $company)
    {
        $this->hostname = $hostname;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->company = $company;
    }

    public function request($class, $service, $method, $arguments = array())
    {
        // Get WSDL URL
        $wsdl_uri = $this->getWsdlUri($class);

        // Init SOAP client
        $this->client = new SoapClient($wsdl_uri, ['trace' => true, 'exceptions' => true]);
        
        // Add authentication to the query
        $request = $this->addAuthHeader($arguments);

        $result = $this->client->__soapcall($service, array($method => $request));

        return $result->ReturnValue;
    }

    public function addAuthHeader($arguments)
    {
        $authentication = [
            'Username'  => $this->username,
            'Password'  => $this->password,
            'Company'   => $this->company,
        ];

        $query = array_merge($authentication, $arguments);

        return $query;
    }

    public function getWsdlUri($service)
    {
        $wsdl_uri = 'http://'.$this->hostname.':'.$this->port.'/schilling/services/'.$service.'Service?wsdl';
        return $wsdl_uri;
    }

    public function getLastRequest()
    {
        return $this->formatXml($this->client->__getLastRequest());
    }

    public function getLastResponse()
    {
        return $this->formatXml($this->client->__getLastResponse());
    }

    public function formatXml($xml)
    {
        $dom = new DOMDocument();
        $dom->formatOutput = true;
        $dom->preserveWhitespace = false;
        $dom->loadXml($xml);

        return $dom->saveXml();
    }
}
