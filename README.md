This application is built to help Shopify Partners gain insights into their shops, apps, billing events, and app events. Built on Laravel, it provides a streamlined interface to manage and view key metrics in one place.


## Features

- **Shops Management**: View and manage insights about different shops.
- **App Management**: Track apps associated with each shop.
- **Billing Events**: Monitor billing events and maintain financial records.
- **App Events**: Keep track of all app events for each shop.


## Getting Started

Follow these instructions to set up and run the application on your local machine.

### Prerequisites

Ensure you have the following installed on your system:

- **PHP**: >= 8.2
- **Composer**
- **Laravel**: >= 11.0
- **MySQL or any other compatible database**

### Installation

1. **Clone the Repository**:
    ```bash
    git clone https://github.com/yourusername/your-repo-name.git
    cd your-repo-name
    ```

2. **Install Dependencies**:
    composer install

3. **Setup Environment**:
    - Copy the `.env.example` file to `.env`.
    - Configure the `.env` file with your database and other relevant settings.

    cp .env.example .env
    php artisan key:generate

4. **Run Migrations**:
    - Set up your database and then run the migrations.

    php artisan migrate

5. **Install Charting Library**:
    - This project utilizes the **Flowframe Laravel Trend** package for generating insights and trends in data.

    composer require flowframe/laravel-trend

6. **Run the Server**:
    php artisan serve
