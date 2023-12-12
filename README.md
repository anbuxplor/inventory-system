# Xplor Inventory System
Laravel Inventory System Technical Challenge.

## Objective
Create a RESTful API for an inventory management system using Laravel and Mysql.

## Required softwares
- **PHP >= 8.1**
- **Laravel 10**
- **MySQL Database**

## Features
- Item Management
- Category Management
- Items and Category association
- Email Notifications
- Authorized by predefined token 
- API request validations
- Phpunit test cases written
- Can check the code coverage report

## Steps to Setup the Application
- Install **composer** in your system
- create .env file in project root folder
- Update MySQL database credentials in .env
- Update **XPLOR_API_TOKEN** value to authendicate
- Update **INVENTORY_ADMIN_EMAIL** and **INVENTORY_TEAM_EMAILS** values to get email notifications
- Open project root folder in terminal
- Run **composer install**
- Run **php artisan config:cache**
- Run **php artisan migrate**
- Run **php artisan serve**
- You can access the application with base url i.e http://127.0.0.1:8000

## Code coverage report
Our inventory system unit tests could be covered or not covered few lines of code. To determine that we can get the report as HTML, by running the below command
```javascript
php artisan test --coverage-html report
```
in project's root folder under report folder the code coverge detail test report will be stored.
This feature requires Xdebug or PCOV - PHP extension. For more details refer [here](https://laravel.com/docs/10.x/testing#reporting-test-coverage)


## Inventory Management APIs Reference

## Authorization

All API requests require the use of a predefined Authorized API key. You can updated your own token value in the .env file XPLOR_API_TOKEN="your_auth_token" .

To authenticate an API request, you should provide your API key in the `Authorization` header as `Bearer your_auth_token`.

If the token is not valid the APIs will show error as **Unauthorized access**.


### Common headers for all APIs

| Authorization | Bearer Token      | Description              |
| :------------ | :------------     | :------------------------- |
| `Token`       | `your_auth_token` | **Required**. Your API AUTH key |

| Request Header    |                   |                            |
| :---------------  | :------------     | :------------------------- |
| `Accept`          | `application/json`   | **Required**.              |
```diff
- Note: These headers are mandatory to access all APIs.
```

### Get all categories

```http
  GET {{base_url}}/api/category
```
#### Request Params: NA

#### Responses
List API returns a JSON response in the following format:

```javascript
{
    "data": [
        {
            "id": 1,
            "name": "category 01",
            "description": "desc",
            "created_at": "2023-12-11 09:08:35",
            "updated_at": "2023-12-11 09:08:35"
        },
        {
            "id": 2,
            "name": "category 02",
            "description": "demo desc..",
            "created_at": "2023-12-11 09:31:38",
            "updated_at": "2023-12-12 05:14:45"
        }
    ],
    "success": true
}
```
#### Get a category

```http
  GET {{base_url}}/api/category/{{category_id}}
```
#### Request Params:
in URL pass the valid {{category_id}}
```javascript
{
    "data": {
        "id": 3,
        "name": "My category",
        "description": "demo",
        "created_at": "2023-12-11 09:31:38",
        "updated_at": "2023-12-12 05:14:45"
    },
    "success": true
}
```


#### Create a category

```http
  POST {{base_url}}/api/category
```
#### Request Params:
| Parameter | Type | Description |
| :--- | :--- | :--- |
| `name` | `string` | **Required**. Your category name |
| `description` | `string` | **Required**. Your category desc |

#### Responses
```javascript
{
    "data": {
        "name": "Category 2",
        "description": "demo desc",
        "updated_at": "2023-12-12 02:38:47",
        "created_at": "2023-12-12 02:38:47",
        "id": 12
    },
    "success": true,
    "message": "Category created successfully"
}
```

#### Update a category

```http
  PUT {{base_url}}/api/category/{{category_id}}
```
#### Request Params:
in URL pass the valid {{category_id}}

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `name` | `string` | **Required**. Your category name |
| `description` | `string` | **Required**. Your category desc |

#### Responses
```javascript
{
    "data": {
        "name": "Category name",
        "description": "demo desc",
        "updated_at": "2023-12-12 02:38:47",
        "created_at": "2023-12-12 02:38:47",
        "id": 12 /* {{category_id}} */
    },
    "success": true,
    "message": "Category updated successfully"
}
```

#### Delete a category

```http
  DELETE {{base_url}}/api/category/{{category_id}}
```
#### Request Params:
in URL pass the valid {{category_id}}

#### Responses
```javascript
{
    "message": "Category deleted successfully",
    "success": true
}
```

### Get all Items

```http
  GET {{base_url}}/api/item
```
#### Request Params: NA

#### Responses
List API returns a JSON response in the following format:

```javascript
{
    "data": [
        {
            "id": 1,
            "name": "Test category",
            "description": "Test description",
            "price": "200.00",
            "quantity": 501,
            "created_at": "2023-12-12 03:29:00",
            "updated_at": "2023-12-12 03:29:00",
            "categories": [
                {
                    "id": 2,
                    "name": "category 02",
                    "description": "desc"
                },
                {
                    "id": 4,
                    "name": "Test category1",
                    "description": "Test description"
                }
            ]
        },
        {
            "id": 2,
            "name": "item 204",
            "description": "222",
            "price": "30.00",
            "quantity": 0,
            "created_at": "2023-12-12 03:29:34",
            "updated_at": "2023-12-12 03:29:34",
            "category": [
                {
                    "id": 2,
                    "name": "category 02",
                    "description": "desc",
                }
            ]
        },
        
    ],
    "success": true
}
```

#### Get an Item

```http
  GET {{base_url}}/api/item/{{item_id}}
```
#### Request Params:
in URL pass the valid {{item_id}}
```javascript
{
    "data": {
        "id": 29,
        "name": "item 2121",
        "description": "222",
        "price": "30.00",
        "quantity": 10,
        "categories": [
            {
                "id": 2,
                "name": "cat 02",
                "description": "desc"
            }
        ],
        "created_at": "2023-12-12 06:25:23",
        "updated_at": "2023-12-12 06:27:39"
    },
    "success": true
}
```

#### Create an Item

```http
  POST {{base_url}}/api/item
```
#### Request Params:
| Parameter | Type | Description |
| :--- | :--- | :--- |
| `name` | `string` | **Required**. Your item name |
| `description` | `string` | **Required**. Your item desc |
| `price` | `number` | **Required**. Your item price. should be greater than or equal to 0. It will allow max of 2 decimal places |
| `quantity` | `number` | **Required**. Your item's available quantity. should be greater than or equal to 0. |
| `category_id` | `array` | **Required**. associated category ids in array like [1,2]. Minimum 1 category should send in payload.|

#### Responses
```javascript
{
    "data": {
        "name": "item demo",
        "description": "222",
        "price": "200.00",
        "quantity": 20,
        "updated_at": "2023-12-12T06:25:23",
        "created_at": "2023-12-12T06:25:23",
        "id": 29
    },
    "success": true,
    "message": "Item created and Email sent successfully!"
}
```

#### Update an item

```http
  PUT {{base_url}}/api/item/{{item_id}}
```
#### Request Params:
in URL pass the valid {{item_id}}

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `name` | `string` | **Required**. Your item name |
| `description` | `string` | **Required**. Your item desc |
| `price` | `number` | **Required**. Your item price. should be greater than or equal to 0. It will allow max of 2 decimal places |
| `quantity` | `number` | **Required**. Your item's available quantity. should be greater than or equal to 0. |
| `category_id` | `array` | **Required**. associated category ids in array like [1,2]. Minimum 1 category should send in payload.|

#### Responses
```javascript
{
    "data": {
        "id": 29,
        "name": "items update",
        "description": "222",
        "price": "30.00",
        "quantity": 10,
        "created_at": "2023-12-12T06:25:23",
        "updated_at": "2023-12-12T06:27:39"
    },
    "success": true,
    "message": "Item updated and Email sent successfully!"
}
```

#### Delete an item

```http
  DELETE {{base_url}}/api/item/{{item_id}}
```
#### Request Params:
in URL pass the valid {{item_id}}

#### Responses
```javascript
{
    "message": "Item deleted successfully",
    "success": true
}
```

## Status Codes

Xplor Inventory API returns the following status codes in its API:

| Status Code | Description |
| :--- | :--- |
| 200 | `OK` |
| 201 | `CREATED` |
| 403 | `UNAUTHORIZED ACCESS` |
| 422 | `UNPROCESSABLE INPUT / VALIDATION ERRORS` |
| 400 | `BAD REQUEST` |
| 404 | `NOT FOUND` |
| 500 | `INTERNAL SERVER ERROR` |