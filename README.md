# API Documentation
This API provides delegation scheduling functionality.

## Accessing the API
The API is available at `https://mindento-scheduler.localhost/`.

## Authentication
No authentication is currently required to access the API.

## HTTP Methods
The API supports the following HTTP methods:

- GET
- POST

## Request and Response Formats
The API accepts and returns data in JSON format.

## Routes
There are implemented following routes:

| Name                 | Method | Scheme | Host | Path                           |
|----------------------|--------|--------|------|--------------------------------|
| create_delegation    | POST   | ANY    | ANY  | /api/delegation                |
| create_employee      | POST   | ANY    | ANY  | /api/employee                  |
| employee_delegations | GET    | ANY    | ANY  | /api/employee/{id}/delegations |


## Starting the API
To start the API, follow these steps:

1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Run `cp .env.sample .env` to create .env file
4. Start the server by running `make start`.
5. The API should now be running and accessible at `https://mindento-scheduler.localhost/`.

## Testing the API
To test the API, follow these steps:

1. Open a new terminal window and navigate to the project directory.
2. Run `make phpunit`.
3. The tests should run and provide feedback on whether they passed or failed.
