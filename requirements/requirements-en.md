## The new feature contains three new API endpoints:
- creating an order,
- completing an order,
- receiving information and recent orders.

### Description of endpoints:

1. **Creating a new order**
    - Request format:

        ```
        {
          "sum": 1000, //общая сумма заказа
          "contractorType": 1, // тип контрагента (юридическое/физическое лицо)
          "items": [{
              "productId": 1, // ID товара
              "price": 1000, // стоимость товара
              "quantity": 1 // количество
            },
            //...
          ]
        }
        ```

    - When an order is created, a unique number is generated in the format
      `{year|2020}-{month|09}-{order number}`. Например `2020-09-12345`
    - After creating an order:
        - **Individuals** should be redirected to the payment page
          `http://some-pay-agregator.com/pay/{order number}`
        - **Legal entities** should be redirected to the payment page
          `http://some-pay-agregator.com/orders/{order number}/bill`


2. **Completing an order**
    - The endpoint verifies the payment for display to the user, and based on the result, the user should see either a thank you page or a reminder to make the payment.:
        - For legal entities - verification of payment via a separate microservice

        - For individuals - verification is performed using a payment flag, a field in the database (data is entered into the database via an external microservice; implementation of data exchange for review code is not provided).


3. **Receiving information about recent orders**
    - The endpoint must return information about the number of orders specified in the request in the following format:

         ```
         [{
             "id": "2020-09-123456", // номер заказа
             "sum": 1000, // общая сумма заказа
             "contractorType": 1, // тип контрагента (юридическое/физическое лицо)
             "items": [{
                 "productId": 1, // ID товара
                 "price": 1000, // стоимость товара
                 "quantity": 1 // количество
               },
               //...
             ]
           },
           // ...
         ]
         ```

### Technical requirements:
- To store data, you should use PDO and the Repository pattern.
