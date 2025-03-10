# 🌍 MarocExplore API

![MarocExplore Banner](https://via.placeholder.com/1200x300.png?text=MarocExplore%20API)  
*Explore Morocco like never before – Create, Share, and Discover Personalized Travel Itineraries!*

---

## 📜 Project Overview

**MarocExplore** is a tourism promotion platform designed to showcase the diverse destinations of Morocco. This API, built with **Laravel** and **PostgreSQL**, powers a system where users can create, manage, and share custom travel itineraries, complete with destinations, activities, and more. Designed with security, scalability, and user experience in mind, this project aims to inspire travelers to explore Morocco’s beaches, mountains, rivers, and monuments.

---

## ✨ Features

### 🔒 Authentication & User Management
- **Sign Up / Login**: Create and authenticate user accounts securely.
- **Bonus**: JWT-based authentication for enhanced security.

### 🗺️ Itinerary Management
- **Create**: Build itineraries with a title, category (e.g., beach, mountain), duration, image, and at least 2 destinations.
- **Edit**: Modify itineraries (only by the creator).
- **Wishlist**: Add itineraries to a personal "To Visit" list.

### 📍 Destination Management
- Add multiple destinations to an itinerary.
- Store details like name, accommodation, and a list of places to visit, activities, or dishes to try.

### 🔍 Search & Browse
- View all available itineraries.
- Filter by category and duration.
- Search itineraries by keywords in the title.

### 🛠️ Testing & Documentation
- Comprehensive unit tests for all API endpoints.
- Postman collection for manual testing.
- Detailed API documentation using Swagger.

### 📊 Query Builder
- Fetch itineraries with their destinations.
- Filter by category and duration.
- Add to "To Visit" list.
- Search by title keyword.
- Retrieve most popular itineraries (by favorites).
- Stats: Total itineraries per category.

### 🌟 Bonus Features
- JWT authentication.
- Rating and commenting system.
- Recommendation engine based on user preferences.

---

## 🛠️ Tech Stack

- **Backend**: Laravel (PHP Framework)
- **Database**: PostgreSQL
- **Authentication**: JWT (JSON Web Tokens)
- **Testing**: PHPUnit (Laravel’s built-in testing suite)
- **Documentation**: Swagger / Postman

---

## 🚀 Getting Started

### Prerequisites
- [PHP](https://www.php.net/) (v8.1+)
- [Composer](https://getcomposer.org/) (Dependency Manager for PHP)
- [PostgreSQL](https://www.postgresql.org/) (v13+)
- [Postman](https://www.postman.com/) for API testing

### Installation
1. **Clone the Repository**
   ```bash
   git clone https://github.com/HamzaBraik01/MarocExplore.git
   cd MarocExplor


## Step 2: Install Dependencies

Install all required dependencies with Composer:

```bash
composer install
```

## Step 3: Set Up Environment Variables

Copy the `.env.example` file to `.env` and configure it:

```bash
cp .env.example .env
```

Update the `.env` file with your PostgreSQL credentials:

```env
APP_NAME=MarocExplore
APP_ENV=local
APP_KEY=  # Generate with `php artisan key:generate`
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=maroc_explore
DB_USERNAME=your_postgres_username
DB_PASSWORD=your_postgres_password
```

## Step 4: Run Database Migrations

Run the database migrations to set up your tables:

```bash
php artisan migrate
```

## Step 5: Generate Application Key

Generate the application key:

```bash
php artisan key:generate
```

## Step 6: Run the Application

Run the application on the local development server:

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`.

## Step 7: Test the API

Import the Postman collection from `docs/postman_collection.json` and start exploring the endpoints!

### 📚 API Endpoints

| Method | Endpoint                     | Description                                 |
|--------|------------------------------|---------------------------------------------|
| POST   | /api/auth/register            | Register a new user                        |
| POST   | /api/auth/login               | Login and get JWT token                    |
| POST   | /api/itineraries              | Create a new itinerary                      |
| GET    | /api/itineraries              | Fetch all itineraries                       |
| GET    | /api/itineraries/{id}         | Fetch a specific itinerary                 |
| PUT    | /api/itineraries/{id}         | Update an itinerary (owner)                |
| POST   | /api/wishlist                 | Add itinerary to wishlist                   |
| GET    | /api/search                   | Search itineraries by filters              |

Full documentation is available in Swagger (link to be added).

## Step 8: Running Tests

Run the following command to execute tests:

```bash
php artisan test
```



