# 🚗 Car Rental System

A comprehensive platform for managing a car rental business.  
The system combines a high-performance API for mobile applications with a hybrid web panel for customers and administrators.  
The entire project is containerized using **Docker**.

---

## 🛠️ Technologies and Rationale (Tech Stack)

### Backend (Logic and Data)
- **PHP 8.2 + Laravel 11:** Chosen for rapid development, strong security, and built-in authentication mechanisms (JWT).  
- **PostgreSQL:** Advanced relational database providing strong data integrity — crucial for reservations and financial transactions.  
- **RabbitMQ:** Message queuing system used for asynchronous task processing (e.g., sending confirmation emails, generating PDF reports), offloading the main application thread.  
- **Swagger (OpenAPI):** Automatic API documentation, essential for collaboration between backend and mobile/frontend teams.

### Frontend (Web Interface)
- **Laravel Blade:** Server-side templating engine ensuring excellent SEO and fast initial page render.  
- **Alpine.js:** A lightweight JavaScript framework used to add interactivity (filters, modals, dynamic API data loading) without building a heavy SPA (Single Page Application).  
- **Tailwind CSS:** A utility-first CSS framework enabling rapid development of responsive and modern interfaces.

### Infrastructure
- **Docker & Docker Compose:** Provide identical runtime environments for all developers, avoiding the *"works on my machine"* issue.

---

## 🚀 Setup Instructions

**Prerequisites:**  
Installed **Docker** and **Git**.

### 1. Clone the repository
```bash
git clone https://github.com/Piti3/car_rental.git
cd car_rental
```

### 2. Environment configuration
Copy the example `.env` file:
```bash
cp .env.example .env
```
Make sure database settings match those in `docker-compose.yml`, e.g.:
```
DB_HOST=postgres
```

### 3. Run the containers
Build and start the environment (may take a few minutes on first run):
```bash
docker-compose up -d --build
```

### 4. Dependencies installation and configuration (inside the container)
Access the application container:
```bash
docker-compose exec app sh
```

Run the following commands inside (`root@...:/var/www/html #`):
```bash
# 1. Fix file permissions for logs and cache
chmod -R 777 storage bootstrap/cache

# 2. Install PHP dependencies
composer install

# 3. Generate the encryption key
php artisan key:generate

# 4. Run database migrations
php artisan migrate

# 5. (Optional) Seed the database with sample data
php artisan db:seed

# 6. Generate Swagger documentation
php artisan l5-swagger:generate
```

Exit the container:
```bash
exit
```

---

## 🌐 Application Access

| Service | URL | Login | Password |
|----------|-----|--------|-----------|
| **Web Application** | [http://localhost:8080](http://localhost:8080) | – | – |
| **API Documentation (Swagger)** | [http://localhost:8080/api/documentation](http://localhost:8080/api/documentation) | – | – |
| **Database (PgAdmin)** | [http://localhost:5050](http://localhost:5050) | [admin@example.com](mailto:admin@example.com) | `admin` |
| **Queues (RabbitMQ UI)** | [http://localhost:15672](http://localhost:15672) | `guest` | `guest` |


---

## 📱 Screenshots


---

## 🗺️ ERD Diagram

![Diagram ERD](/screenshots/Diagram_ERD.png)


