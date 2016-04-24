<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use LasseLehtinen\SchillingSoapWrapper\Services\Lookup;
use LasseLehtinen\SchillingSoapWrapper\Services\Product;
use PHPUnit_Framework_Assert as PHPUnit;

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
    private $debtor_number;
    private $new_product_number;
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
        PHPUnit::assertInternalType('array', $this->response->ReturnValue);

        $random_key = array_rand($this->response->ReturnValue);
        $random_product = $this->response->ReturnValue[$random_key];
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
        $random_key = array_rand($this->response->ReturnValue);
        $random_internet_category_id = $this->response->ReturnValue[$random_key];
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

    /**
     * @Given I have a random customer number
     */
    public function iHaveARandomCustomerNumber()
    {
        $this->iSendALookupRequestForDomain(5);
        PHPUnit::assertInternalType('array', $this->response->ReturnValue);

        $random_key = array_rand($this->response->ReturnValue);
        $random_debtor = $this->response->ReturnValue[$random_key];
        $this->debtor_number = $random_debtor->KeyValue;

        PHPUnit::assertInternalType('string', $this->debtor_number);
    }

    /**
     * @Given then send a query for customer prices for the product
     */
    public function thenSendAQueryForCustomerPricesForTheProduct()
    {
        $this->response = $this->product->getCustomerPrices(['DebtorNumber' => $this->debtor_number, 'ProductNumbers' => $this->product_number]);
    }

    /**
     * @Given that I have have template product :template_product
     */
    public function thatIHaveHaveTemplateProduct($template_product)
    {
        $this->response = $this->lookup->lookup(['DomainNumber' => 7, 'KeyValue' => $template_product]);
        PHPUnit::assertEquals($template_product, $this->response->KeyValue);
    }

    /**
     * @Given that I create a new product number
     */
    public function thatICreateANewProductNumber()
    {
        $this->new_product_number = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 16);
        PHPUnit::assertInternalType('string', $this->new_product_number);
    }

    /**
     * @Then send a query to create a product
     */
    public function sendAQueryToCreateAProduct()
    {
        // Get random existing supplier
        $supplier = $this->lookup->lookup(['DomainNumber' => 127]);

        $random_supplier_key = array_rand($supplier->ReturnValue);
        $random_supplier = $supplier->ReturnValue[$random_supplier_key];

        // Get random product group
        $product_group = $this->lookup->lookup(['DomainNumber' => 256]);

        if (is_object($product_group)) {
            $random_product_group = $product_group;
        } else {
            $random_product_group = $product_group[array_rand($product_group)];
        }

        $this->response = $this->product->saveProductWithReturnData([
            'Action' => 1,
            'ProductText' => 'Test product - Can be deleted',
            'ProductNumber' => $this->new_product_number,
            'PrimarySupplier' => $random_supplier->DataValue,
            'ProductGroup' => $product_group->KeyValue,
        ]);
    }

    /**
     * @Then product in the response should have the new product number as ProductNumber
     */
    public function productInTheResponseShouldHaveTheNewProductNumberAsProductnumber()
    {
        PHPUnit::assertEquals($this->new_product_number, $this->response->ProductNumber);
        PHPUnit::assertEquals('Test product - Can be deleted', $this->response->ProductText);
    }
}
