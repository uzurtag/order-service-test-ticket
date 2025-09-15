
## Setup project

clone repo

run project `sail up`
run migrations `sail artisan migrate`

check yourself in a postman

1. create order use `POST /api/order/` 
```
Example body:

{
    "sum": 1000,
    "contractorType": 1,
    "items": [
        {
            "productId": 1, 
            "price": 1000, 
            "quantity": 1 
        },
        {
            "productId": 2,
            "price": 1010,
            "quantity": 1 
        }
    ]
}
```
2. get latest orders `GET /api/order/latest`
