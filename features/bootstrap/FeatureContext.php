<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as PHPUnit;
use LasseLehtinen\SchillingSoapWrapper\Services\Lookup;
use LasseLehtinen\SchillingSoapWrapper\Services\Product;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    private $lookup;
    private $product;
    private $response;
    private $message;

    // Random values like product numbers etc.
    private $product_number;
    private $internet_category_id;

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

        $this->product = new Product(
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

    /**
     * @Given I have a random product number
     */
    public function iHaveARandomProductNumber()
    {
        $this->iSendALookupRequestForDomain(7);
        PHPUnit::assertInternalType('array', $this->response);

        $random_key = array_rand($this->response);
        $random_product = $this->response[$random_key];
        $this->product_number = $random_product->KeyValue;
        
        PHPUnit::assertInternalType('string', $this->product_number);
    }

    /**
     * @Given then send a query for a product
     */
    public function thenSendAQueryForAProduct()
    {
        $this->response = $this->product->getProducts(['ProductNumber' => $this->product_number]);
    }

    /**
     * @Then the responses product number should match the query
     */
    public function theResponsesProductNumberShouldMatchTheQuery()
    {
        PHPUnit::assertEquals($this->response->ProductNumber, $this->product_number);
    }

    /**
     * @Then Product text should be a string
     */
    public function productTextShouldBeAString()
    {
        PHPUnit::assertGreaterThanOrEqual(1, strlen($this->response->ProductText));
        PHPUnit::assertInternalType('string', $this->response->ProductText);
    }

    /**
     * @Given I send request for all product internet categories
     */
    public function iSendRequestForAllProductInternetCategories()
    {
        $this->response = $this->product->getProductInternetCategories();
    }

    /**
     * @Given I have a random internet category id
     */
    public function iHaveARandomInternetCategoryId()
    {
        $this->response = $this->product->getProductInternetCategories();
        $random_key = array_rand($this->response);
        $random_internet_category_id = $this->response[$random_key];
        $this->internet_category_id = $random_internet_category_id->CategoryId;
    }

    /**
     * @Given then send a query for an internet category
     */
    public function thenSendAQueryForAnInternetCategory()
    {
        $this->response = $this->product->getInternetCategories(['CategoryId' => $this->internet_category_id]);
    }

    /**
     * @Then the responses internet category id matches the query
     */
    public function theResponsesInternetCategoryIdMatchesTheQuery()
    {
        PHPUnit::assertEquals($this->response->CategoryId, $this->internet_category_id);
    }

    /**
     * @Given then send a query for discount information
     */
    public function thenSendAQueryForDiscountInformation()
    {
        $this->response = $this->product->getDiscountInformation(['ProductNumber' => $this->product_number]);
    }
}
