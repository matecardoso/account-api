
# Account API Project

Hello! This project is an API built with Laravel Lumen to manage account operations like deposits, withdrawals, balance inquiries, and transfers. We use a caching system to temporarily store account data.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Testing](#testing)

## Installation

1. **Requirements:**

   Ensure you have the following dependencies installed:
   - PHP >= 8.0
   - OpenSSL PHP Extension
   - PDO PHP Extension
   - Mbstring PHP Extension
   - Composer

2. **Clone the Repository:**

   First, clone the repository to your local machine:

   ```bash
   git clone https://github.com/your-username/account-api.git
   cd account-api
   ```

3. **Install Dependencies:**

   Make sure you have Composer installed, then run:

   ```bash
   composer install
   ```

4. **Environment Setup:**

   Copy the example environment file and adjust as needed:

   ```bash
   cp .env.example .env
   ```

5. **Create Cache Directory:**

   Ensure the cache directory exists and is writable:

   ```bash
   mkdir -p storage/framework/cache/data
   chmod -R 775 storage/framework/cache
   ```

## Usage

### Starting the Server

You can start the Lumen development server with the following command:

```bash
php -S localhost:8000 -t public
```

Your API will be available at `http://localhost:8000`.

## API Endpoints

### POST `/reset`

Resets the API state, clearing all cached data.

**Response:**
- `200 OK`

### GET `/balance?account_id={id}`

Fetches the balance of the specified account.

**Parameters:**
- `account_id`: The ID of the account.

**Response:**
- `200 OK` with the balance if the account exists.
- `404 Not Found` with `0` if the account does not exist.

### POST `/event`

Handles account events such as deposit, withdraw, and transfer.

**Request Body:**
- `type`: The type of event (`deposit`, `withdraw`, `transfer`).
- `destination`: The account ID for deposit and transfer events.
- `origin`: The account ID for withdraw and transfer events.
- `amount`: The amount to deposit, withdraw, or transfer.

**Response:**
- `201 Created` with the account data if successful.
- `404 Not Found` if the specified account does not exist (for withdraw and transfer events).

## Testing

This project uses PHPUnit for testing. The main test file is `tests/AccountEventTest.php`.

Here are some of the test cases included:

- `testResetState`: Tests the `/reset` endpoint.
- `testGetBalanceForNonExistingAccount`: Tests the `/balance` endpoint with a non-existing account.
- `testCreateAccountWithInitialBalance`: Tests creating an account with an initial deposit.
- `testDepositIntoExistingAccount`: Tests depositing into an existing account.
- `testGetBalanceForExistingAccount`: Tests retrieving the balance of an existing account.
- `testWithdrawFromNonExistingAccount`: Tests withdrawing from a non-existing account.
- `testWithdrawFromExistingAccount`: Tests withdrawing from an existing account.
- `testTransferFromExistingAccount`: Tests transferring funds between existing accounts.
- `testTransferFromNonExistingAccount`: Tests transferring from a non-existing account.

To run the tests, execute:

```bash
vendor/bin/phpunit
```
