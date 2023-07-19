# CurrencyCbr

CurrencyCbr is a PHP application that allows you to retrieve currency exchange rates information.

## Installation

To install the project, follow the instructions below:

1. Clone the repository:
    ```
    git clone https://github.com/DaniilZakharenko/CurrenciesCbr.git
    ```
2. Build the Docker images:
    ```
    docker-compose build
    ```
3. Enter the Docker container shell:
    ```
    docker-compose exec php sh
    ```
4. Install dependencies:
    ```
    composer install
    ```

## Application Usage

To use the application, run the following commands:

1. Initialize:
    ```
    php init.php
    ```
2. Add 180 messages to the queue:
    ```
    php consumer.php
    ```
3. Start the queue worker:
    ```
    php worker.php
    ```
