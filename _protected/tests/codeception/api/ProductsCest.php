<?php

use app\models\User;

class ProductsCest {

    private $url = '/products';
    
    public function _before(ApiTester $I) {
        $I->authenticateToApi('tester');
    }

    // tests
    public function listProductViaAPITest(\ApiTester $I) {
        $I->wantTo('List products first page');
//        $user = $I->seeInDatabase('users', ['username' => 'test']);

        $I->sendGET($this->url);
        $I->checkResponse('[{"id":1,"title":"Fallout","price":"1.99"},{"id":2,"title":"Don’t Starve","price":"2.99"},{"id":3,"title":"Baldur’s Gate","price":"3.99"}]');

        $I->amGoingTo('List products second page');
        $I->sendGET($this->url, ['page' => 2]);
        $I->checkResponse('[{"id":4,"title":"Icewind Dale","price":"4.99"},{"id":5,"title":"Bloodborne","price":"5.99"}]');
    }

    public function createProductViaAPITest(\ApiTester $I) {
        $I->wantTo('Create product and remove');
        $I->sendPOST($this->url,['title'=>'Nowy','price'=>11.99]);
        $I->sendGET($this->url, ['page' => 2]);
        $I->checkResponse('"title":"Nowy","price":"11.99"');
        $resp = $I->grabResponse();
        if(preg_match('/"id":(\d+),"title":"Nowy"/', $resp,$matches)){
          $id = $matches[1];
        } 
        
        $I->amGoingTo('Create same product - unique check');
        $I->sendPOST($this->url,['title'=>'Nowy','price'=>11.99]);
        $I->checkResponse('[{"field":"title","message":"Title \"Nowy\" has already been taken."}]',\Codeception\Util\HttpCode::UNPROCESSABLE_ENTITY);
        
        $I->amGoingTo('Delete product');
        $I->sendDELETE($this->url.'/'.$id);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT); // 200
    }
    
    public function updateProductViaAPITest(\ApiTester $I) {
        $I->wantTo('Update product');
        $id = 4;
        $I->amGoingTo("Modify title of product");
        $I->sendPUT($this->url.'/'.$id,['title'=>'Icewind Dale Modified']);
        
        $I->amGoingTo("Check for modifications");
        $I->sendGET($this->url, ['page' => 2]);
        $I->checkResponse('"Icewind Dale Modified"');
        
        $I->amGoingTo("Restore old name");
        $I->sendPUT($this->url.'/'.$id,['title'=>'Icewind Dale']);
        
        $I->amGoingTo("Check for modifications");
        $I->sendGET($this->url, ['page' => 2]);
        $I->checkResponse('"Icewind Dale"');
    }

}
