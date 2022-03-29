
  
# Employee Management System

> ### Employee Management System API documentation.

---

# Description

[Employee Management System](https://github.com/sakarious/ems) API Backend for Employee Management System.

---

# Setup

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation)

Clone the repository

    git clone https://github.com/sakarious/ems.git

Switch to the repo folder

    cd ems


Create a '.env' file and copy the .env.example file and make the required configuration changes in the .env file. The API uses a MySQL database. Download and install [XAMPP](https://www.apachefriends.org/download.html) and create a database. Replace the following in your .env file

-   DB_CONNECTION=mysql
-   DB_HOST=127.0.0.1
-   DB_PORT=3306
-   DB_DATABASE={DATABASE CREATED WITH XAMPP}
-   DB_USERNAME=root
-   DB_PASSWORD=(Put in your password if any else leave blank)

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate
    
 Run the database seed; This creates the admin's account. (**Set the database connection in .env before running this command**)

    php artisan db:seed

Start the local development server

    php artisan serve

The api can be accessed at [http://localhost:8000/api/v1](http://localhost:8000/api/v1).


---

# API Specification

## Features

-   Register.
-   Login
-   Profile.
-   Get Wallet
-   Transfer
-   Get Transfer History

## Allowed HTTP Requests

-   GET
-   POST

## Base URL

`http://localhost:8000/api`
**Set 'Accept' to 'application/json' in http header for all requests**

## Endpoints

-   `POST: /register` - Admin can register/ create an account for an employee. Default Password is created user last name in lowercase
-   `POST: /login` - Login and get an access token for authorization.
-   `GET: /profile` - Authenticated user can see profile.
-   `GET: /getwallet` - Authenticated users can see wallet details/balance.
-   `POST: /transfer` - Admin can transfer funds/ pay salary to employee(s).
-   `GET: /gethistory?status=failed` - Admin can search payment history by payment status.
-   `GET: /gethistory?user=2` - Admin can search payment history by user id.
-   `GET: /gethistory` - Employees can see payment history.

## Resources

### Register

---

Creates an employee Account and Virtual Wallet


-   **URL and Method**
    `POST http://localhost:8000/api/v1/register`

  **Required:**

`Authorization: Bearer ACCESS_TOKEN` 
(Login as Admin to get Token.  Admin email is: "admin@ems.com" && password is: "password")

-   **Request**

```
POST http://localhost:8000/api/v1/register
Accept: application/json

{
    "firstname": "Saka",
    "middlename": "Doe",
    "lastname": "Rious",
    "dob": "1960-07-01",
    "email": "employee@companyname.com",
    "street_address": "Street Address",
    "city": "Ikeja",
    "state": "Lagos",
    "country": "Nigeria",
    "phone_number": "+23410203040",
    "gender": "male",
    "job_title": "Software Engineer",
    "department": "Innovation and Software Development"
}
```

With the following fields:

| Parameter       | Required? | Description              |
| --------------- | --------- | ------------------------ |
| firstname            | required  | User's First name.            |
| middlename            | required  | User's Middle name.            |
| lastname            | required  | User's Last name.            |
| dob            | required  | User's Date of Birth (In Year-month-day).            |
| email           | required  | User's email             |
| street_address        | required  | User's Street Address          |
| city        | required  | User's City.        |
| state        | required  | User's State.         |
| country        | required  | User's Country.         |
| phone_number        | required  | User's Phone Number         |
| gender        | required  | User's Gender.         |
| job_title        | required  | User's Job Title.         |
| department        | required  | User's Department.         |


-   **Success Response**

```
Status 201 Created
Content-Type: application/json; charset=utf-8

{
  "status": "Success",
  "message": "Employee Account and Virtual Wallet Created Successfully Created Successfully. Password is User's last name"
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| message    | string | Response Message.                  |                    |
| status            | string   | Status of response |


-   **Possible errors**

| Error code                | Description                                     |
| ------------------------- | ----------------------------------------------- |
| 400 Bad Request           | Required fields were invalid, validation failed |
| 403 Forbidden          | Access Denied - This means Unauthorized user. Route is only accessible to administrator. |
| 500 Internal Server Error | Server Error                                    |
---

### Login

---

Returns json data of access token.


-   **URL and Method**
    `POST http://localhost:8000/api/v1/login`

-   **Request**

```
POST http://localhost:8000/api/v1/login
Accept: application/json

{
  "email": "admin@ems.com",
  "password": "password"
}
```

With the following fields:

| Parameter       | Required? | Description              |
| --------------- | --------- | ------------------------ |
| email           | required  | User email             |
| password        | required  | User Password.            |


-   **Success Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": "Success",
  "message": "Login Successful",
  "access_token": "4|rGePvFCcCM1SSSAwrQDxo91NnxLsuoB7cIgjaOk5",
  "token_type": "Bearer"
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| message    | string | Response Message.                  |                    |
| status           | string  | Response Status |                           |
| token_type         | string    | Token Type.                       |
| accessToken | string  | User access token                     |


-   **Possible errors**

| Error code                | Description                                     |
| ------------------------- | ----------------------------------------------- |
| 400 Bad Request           | Required fields were invalid, validation failed |
| 500 Internal Server Error | Server Error                                    |
---

### Profile

---

Returns authenticated user details


-   **URL and Method**
    `GET http://localhost:8000/api/v1/profile`

 - **Required:**

   `Authorization: Bearer ACCESS_TOKEN` 

-   **Request**

```
GET http://localhost:8000/api/v1/profile
Accept: application/json
Authorization: Bearer ACCESS_TOKEN

```

-   **Success Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "id": 2,
  "firstname": "Saka",
  "middlename": "Doe",
  "lastname": "Rious",
  "dob": "1960-07-01",
  "email": "employee@companyname.com",
  "street_address": "Street Address",
  "city": "Ikeja",
  "state": "Lagos",
  "country": "Nigeria",
  "phone_number": "+23410203040",
  "gender": "male",
  "job_title": "Software Engineer",
  "department": "Innovation and Software Development"
}
```

Where Response Object is Users Details.


-   **Possible errors**

| Error code                | Description                                     |
| ------------------------- | ----------------------------------------------- |
| 401 Unauthorized           | Unauthenticated.- Check token or Login. |
| 500 Internal Server Error | Server Error                                    |
---

### Get Wallet

---

Returns json data of wallet.

-   **URL and Method**
    `GET http://localhost:8000/api/v1/getwallet`


-   **Request**

```
GET http://localhost:8000/api/v1/getwallet
Accept: application/json
Authorization: Bearer ACCESS_TOKEN

```

-   **Success Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": "Success",
  "message": "Wallet Details",
  "wallet": {
    "id": 2,
    "created_at": "2022-03-29T11:52:01.000000Z",
    "updated_at": "2022-03-29T13:24:06.000000Z",
    "balance": 100004000,
    "user_id": 2
  }
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| status    | string | Response Status Message.                  |
| message    | string | Response Message.                  | 
| wallet    | object | Wallet object                  |                     

-   **Possible errors**

| Error code                | Description                                     |
| ------------------------- | ----------------------------------------------- |
| 401 Unauthorized           | Unauthenticated.- Check token or Login. |
| 500 Internal Server Error | Server Error                                    |
---

### Transfer

---

Returns json data of payment processed.

-   **URL and Method**
    `POST http://localhost:8000/api/v1/transfer`

    **Required:**
`Authorization: Bearer ACCESS_TOKEN`

-   **Request**

```
POST http://localhost:8000/api/v1/transfer
Accept: application/json
Authorization: Bearer ACCESS_TOKEN

{
    "transfers": [
        {"wallet": 2, "amount": 100000000},
        {"wallet": 20, "amount": 1000},
        ]
}
```

With the following fields:

| Parameter       |  Type | Required? | Description              |
| --------------- | --------- | --------- | ------------------------ |
| transfers          | Array  | required  | Transfers is a array of object It contains objects of payments to be made to employee. Object must contain waillet and amount. Wallet value is employee id or wallet id and amount is amount of money to be paid to employee             |


-   **Success Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": "Success",
  "message": "Transfer Processed",
  "all": 7,
  "successful": 1,
  "failed": 6
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| status          | string   | Status of request                        |
| message            | string  | Response Message |
| all             | number  | Number of payment Processed               |
| successful           | number   | Number of Successful Payments                             |
| failed            | number   | Number of Failed Payments                                                  |


-   **Request Successful But Admin Wallet is empty** - Simply means there's no funds to process payment.

```
Status 200 OK
{
  "message": "Insufficient Funds"
}
```

-   **Possible errors**

| Error code                | Description                                     |
| ------------------------- | ----------------------------------------------- |
| 400 Bad Request           | Required fields were invalid, validation failed |
| 401 Unauthorized           | Unauthenticated.- Check token or Login. |
| 403 Forbidden          | Access Denied - This means Unauthorized user. Route is only accessible to administrator. |
| 500 Internal Server Error | Server Error                                    |

---

### List all Transfer History 

---

Returns json data of all payment history made to authenticated user.

-   **URL and Method**
    `GET http://localhost:8000/api/v1/gethistory`

    **Required:**

`Authorization: Bearer ACCESS_TOKEN`

-   **Success Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": "Success",
  "message": "Transfer History",
  "data": [
    {
      "id": 2,
      "user_id": 20,
      "amount": 1000,
      "sent": 1,
      "status": "failed",
      "created_at": "2022-03-29T12:02:58.000000Z",
      "updated_at": "2022-03-29T12:02:58.000000Z"
    },
    {
      "id": 3,
      "user_id": 30,
      "amount": 1000,
      "sent": 1,
      "status": "failed",
      "created_at": "2022-03-29T12:02:58.000000Z",
      "updated_at": "2022-03-29T12:02:58.000000Z"
    }...
  ]
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| status          | string   | Status of request                        |
| message            | object   | Response Message |
| data             | Array   | List all Payment made to employee           |


### FOR ADMIN - Get Payment History By Status or User/Wallet ID

---

Returns json data of payment processed according to query parameter

-   **URL and Method**
    `GET http://127.0.0.1:8000/api/gethistory?status=failed`
    **OR**
    `GET http://127.0.0.1:8000/api/gethistory?user=2`

-   **Query Params**
    `?status=failed OR success`
    **OR**
   `?user=USER/WALLET ID`

    **Required:**

`Status or User in query paramaeter`

`Authorization: Bearer ACCESS_TOKEN`

-   **Success Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": "Success",
  "message": "Transfer History",
  "data": [
    {
      "id": 2,
      "user_id": 20,
      "amount": 1000,
      "sent": 1,
      "status": "failed",
      "created_at": "2022-03-29T12:02:58.000000Z",
      "updated_at": "2022-03-29T12:02:58.000000Z"
    },
    {
      "id": 3,
      "user_id": 30,
      "amount": 1000,
      "sent": 1,
      "status": "failed",
      "created_at": "2022-03-29T12:02:58.000000Z",
      "updated_at": "2022-03-29T12:02:58.000000Z"
    }...
  ]
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| status          | string   | Status of request                        |
| message            | object   | Response Message |
| data             | Array   | List all Payment made to employee           |


