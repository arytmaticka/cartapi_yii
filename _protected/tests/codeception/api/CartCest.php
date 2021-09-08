<?php

class CartCest {

    private $url = '/cart';
    private $testUser = 'tester';

    public function _before(ApiTester $I) {
        $I->authenticateToApi($this->testUser);
    }

    // tests
    public function createCartViaAPITest(ApiTester $I) {
        $I->wantTo('Create a cart');
        $I->amGoingTo('Add existing product to cart with proper amount');
        $I->sendPUT($this->url, ['product_id' => 4, 'amount' => 7]);
        $I->checkResponse('First you need to create a cart.', \Codeception\Util\HttpCode::METHOD_NOT_ALLOWED);
        $I->sendPOST($this->url, []);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED);
        $I->seeResponseIsJson();
    }

    public function cartFunctionsToCartViaAPITest(ApiTester $I) {
        $I->wantTo('Add and remove products');

        $I->amGoingTo('Add existing product to cart with proper amount');
        $I->sendPUT($this->url, ['product_id' => 4, 'amount' => 7]);

        $I->amGoingTo('Add existing product to cart which have this product');
        $I->sendPUT($this->url, ['product_id' => 4, 'amount' => 1]);
        $I->sendGET($this->url);
        $I->checkResponse('[{"product_id":4,"amount":8}]');

        $I->sendPUT($this->url, ['product_id' => 4, 'amount' => 3]); //ERROR - limit 10
        $I->checkResponse('Unable to save cart position', \Codeception\Util\HttpCode::METHOD_NOT_ALLOWED);

        $I->amGoingTo('Add existing product to cart with wrong amount');
        $I->sendPUT($this->url, ['product_id' => 3, 'amount' => 11]); //ERROR - limit 10
        $I->checkResponse('Unable to save cart position', \Codeception\Util\HttpCode::METHOD_NOT_ALLOWED);

        $I->amGoingTo('Add non existing product to cart');
        $I->sendPUT($this->url, ['product_id' => 11, 'amount' => 1]); // ERROR - wrong ID
        $I->checkResponse('Unable to find product with product_id: 11', \Codeception\Util\HttpCode::NOT_FOUND);

        $I->amGoingTo('Check cart list');
        $I->sendGET($this->url);
        $I->checkResponse('[{"product_id":4,"amount":8}]');

        $I->amGoingTo('Add second product to cart with proper amount');
        $I->sendPUT($this->url, ['product_id' => 3, 'amount' => 2]);

        $I->amGoingTo('Add third product to cart with proper amount');
        $I->sendPUT($this->url, ['product_id' => 5, 'amount' => 4]);
        $I->sendGET($this->url);
        $I->checkResponse('{"products":[{"product_id":3,"amount":2},{"product_id":4,"amount":8},{"product_id":5,"amount":4}],"total":"71.86"}');

        $I->amGoingTo('Add forth product to cart with proper amount');
        $I->sendPUT($this->url, ['product_id' => 1, 'amount' => 4]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::METHOD_NOT_ALLOWED);


        $I->amGoingTo('Delete product existing in cart');
        $I->sendDELETE($this->url, ['product_id' => 4]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT);
        $I->sendDELETE($this->url, ['product_id' => 2]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NOT_FOUND);
        $I->sendDELETE($this->url, ['product_id' => 5]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT);
        $I->sendDELETE($this->url, ['product_id' => 3]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT);
        $I->sendGET($this->url);
        $I->checkResponse('[]');
    }

}
