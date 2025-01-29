This application is built to help Shopify Partners gain insights into their shops, apps, billing events, and app events. Built on Laravel, it provides a streamlined interface to manage and view key metrics in one place.

## Features  

- **Shops Management** – View and manage insights about different shops.  
- **App Management** – Track apps associated with each shop.  
- **Billing Events** – Monitor billing events and maintain financial records.  
- **App Events** – Keep track of all app events for each shop.  
- **Affiliate Program** – Manage and track affiliate referrals and commissions.  

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
    ```bash
    composer install
    ```

3. **Setup Environment**:
    - Copy the `.env.example` file to `.env`.
    - Configure the `.env` file with your database and other relevant settings.

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Run Migrations**:
    - Set up your database and then run the migrations.

    ```bash
    php artisan migrate
    ```

5. **Install Charting Library**:
    - This project utilizes the **Flowframe Laravel Trend** package for generating insights and trends in data.

    ```bash
    composer require flowframe/laravel-trend
    ```
    
6. **Start MailDev (for local email testing)**:

    Run the following command to start MailDev:  
    
    ```sh
    maildev
    ```  

7. **Run the Server**:
    ```bash
    php artisan serve
    ```

The application should now be accessible at http://127.0.0.1:8000.
You can access the MailDev web interface at http://localhost:1080.



