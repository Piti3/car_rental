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