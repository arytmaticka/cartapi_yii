Cart-API
===================

Simple application allowing adding products to the cart. The application consist of two HTTP APIs.  

1. Products catalog API:
The API expose methods to:
* Add a new product
    * Product name are unique
* Remove a product
* Update product title and/or price
* List all of the products
* List is paginated, max 3 products per page

2. Cart API
API allow adding products to the carts. User can add multiple items of the same product (max 10 units of the same product).
This API expose methods:
* Create a cart
* Add a product to the cart
* Remove product from the cart
* List all the products in the cart
    * User is not be able to add more than 3 products to the cart
    * Returning total price of all the products in the cart

Installation
-------------------
>I am assuming that you know how to: install and use Composer, and install additional packages/drivers that may be needed for you to run everything on your system. 

1. Running docker image with docker-compose in project folder: ``` docker-compose up ```

2. Run browser on http://127.0.0.1

3. Log in using default login and password. There is exposed mysql database cartapi on port 3306 and testing database cartapi_test on port 3307

4. Fallow the instructions 


Testing
-------------------

1. Run tests: ``` docker-compose exec php sh -c "cd /app/web/_protected/tests && ../vendor/codeception/codeception/codecept run api" ```