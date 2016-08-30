<?php

namespace LasseLehtinen\SchillingSoapWrapper;

use DOMDocument;
use SoapClient;

class SchillingSoapWrapper
{
    private $client;
    private $hostname;
    private $port;
    private $username;
    private $password;
    private $company;

    /**
     * Constructor.
     *
     * @param string $hostname
     * @param int    $port
     * @param string $username
     * @param string $password
     * @param int    $company
     */
    public function __construct($hostname, $port, $username, $password, $company)
    {
        $this->hostname = $hostname;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->company = $company;
    }

    /**
     * Forms and send a Web Service request to Schilling.
     *
     * @param string $class
     * @param string $service
     * @param string $method
     * @param array  $arguments
     *
     * @return array
     */
    public function request($class, $service, $method, $arguments = [])
    {
        // Get WSDL URL
        $wsdl = $this->getWsdlUri($class);

        // Init SOAP client
        $this->client = new SoapClient($wsdl, [
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'trace' => true,
            'exceptions' => true,
            'cache_wsdl' => WSDL_CACHE_MEMORY,
        ]);

        // Add authentication to the query
        $request = $this->addAuthHeader($arguments);

        $result = $this->client->__soapcall($service, array($method => $request));

        if (!isset($result->ReturnValue)) {
            return;
        } else {
            // If more than one, return all return values
            if (count($result->ReturnValue) > 1) {
                return $result;
            } else {
                return $result->ReturnValue;
            }
        }
    }

    /**
     * Adds authentication information to the Web Service query.
     *
     * @param array $arguments
     */
    public function addAuthHeader($arguments)
    {
        $authentication = [
            'Username' => $this->username,
            'Password' => $this->password,
            'Company' => $this->company,
        ];

        $query = array_merge($authentication, $arguments);

        return $query;
    }

    /**
     * Forms and URI for the WSDL.
     *
     * @param string $service
     *
     * @return string
     */
    public function getWsdlUri($service)
    {
        $wsdl = 'http://' . $this->hostname . ':' . $this->port . '/schilling/services/' . $service . 'Service?wsdl';

        return $wsdl;
    }

    /**
     * Returns the last Web Service request as formatted XML.
     *
     * @return string
     */
    public function getLastRequest()
    {
        return $this->formatXml($this->client->__getLastRequest());
    }

    /**
     * Returns the last response as formatted XML.
     *
     * @return string
     */
    public function getLastResponse()
    {
        return $this->formatXml($this->client->__getLastResponse());
    }

    /**
     * Formats the Web Service query/response XML.
     *
     * @param string $xml
     *
     * @return string
     */
    public function formatXml($xml)
    {
        $dom = new DOMDocument();
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $dom->loadXml($xml);

        return $dom->saveXml();
    }
}
