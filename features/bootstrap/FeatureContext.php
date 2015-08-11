<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as PHPUnit;
use LasseLehtinen\SchillingSoapWrapper\Services\Lookup;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    private $lookup;
    private $response;
    private $message;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $dotenv = new Dotenv\Dotenv(__DIR__);
        $dotenv->load();

        $this->lookup = new Lookup(
            getenv('SCHILLING_WEB_SERVICES_HOSTNAME'),
            getenv('SCHILLING_WEB_SERVICES_PORT'),
            getenv('SCHILLING_WEB_SERVICES_USERNAME'),
            getenv('SCHILLING_WEB_SERVICES_PASSWORD'),
            getenv('SCHILLING_WEB_SERVICES_COMPANY')
        );
    }

    /**
     * @Given I send a Lookup request for domain :domain_number
     */
    public function iSendALookupRequestForDomain($domain_number)
    {
        try {
            $this->response = $this->lookup->lookup(['DomainNumber' => $domain_number]);
        } catch (Exception $e) {
            $this->message = $e->getMessage();
        }
    }

    /**
     * @Then the response should have atleast :amount values
     */
    public function theResponseShouldHaveAtleastValues($amount)
    {
        PHPUNit::assertGreaterThanOrEqual($amount, count($this->response));
    }

     /**
     * @Then the request should throw an exception that contains message :content
     */
    public function theRequestShouldThrowAnExceptionThatContainsMessage($content)
    {
        PHPUnit::assertContains($content, $this->message);
    }

    /**
     * @Given I send a Lookup request for domain :domain_number with additional key :additional_key
     */
    public function iSendALookupRequestForDomainWithAdditionalKey($domain_number, $additional_key)
    {
        try {
            $this->response = $this->lookup->lookup(['DomainNumber' => $domain_number, 'AdditionalKeys' => ['DomainNumber' => $additional_key]]);
        } catch (Exception $e) {
            $this->message = $e->getMessage();
        }
    }
}
