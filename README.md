### Installation Instructions

---

Clone into `Defaultworkspace` folder.

Run `composer install`

Modify `.env.example` with proper values and rename to `.env`

Run `php -S localhost:9999 -t public` from the root of `orderapp-api` folder.

Test with `curl` commands:

`curl -X GET http://localhost:9999` should return customers in JSON.

`curl -X POST -F firstname="First" -F lastname="Last" http://localhost:9999` should insert a new customer.

`curl -X DELETE http://localhost:9999?id=1` should remove customer 1 from the database.
