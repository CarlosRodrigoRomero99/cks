# Full-Stack BlueSky Post Explorer

This is a full-stack web application that integrates with the **BlueSky API** to fetch and display posts. The project consists of:
- **Backend**: Laravel API to interact with BlueSky
- **Frontend**: Vue.js for displaying search results in a modern UI

## 🚀 Tech Stack
- **Backend**: Laravel 10, PHP, MySQL, GuzzleHTTP
- **Frontend**: Vue.js 3, Vite, TailwindCSS, Axios
- **Deployment**: Railway (Backend), Netlify/Vercel (Frontend)

---

## 📂 Project Structure
```
/fullstack-project
  ├── backend/  (Laravel API)
  ├── frontend/ (Vue.js Frontend)
```

---

## 🛠️ Setup Instructions

### 1️⃣ Backend (Laravel API)

#### **Prerequisites**
- PHP 8+
- Composer
- MySQL (if needed)

#### **Setup Backend**
```sh
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed  # (If using a database)
php artisan serve
```

#### **Test API Endpoint**
Open in browser: `http://127.0.0.1:8000/search?q=laravel`

---

### 2️⃣ Frontend (Vue.js)

#### **Prerequisites**
- Node.js 18+
- NPM

#### **Setup Frontend**
```sh
cd frontend
npm install
npm run dev
```

#### **Access the UI**
Open: `http://localhost:5173/`

---

## 🔗 API Documentation

### **Search Posts**
```http
GET /search?q={keyword}&since={dateformat}&until={dateformat}&page=1&limit=5&sort=latest
```
**Query Parameters:**
- `q` (string) – Required search keyword
- `since` (date) – Filter from that date onwards
- `until` (date) – Filter until that date
- `page` (integer) – Pagination page number
- `limit` (integer) – Number of results per page
- `sort` (latest, top) – Sort results by latest date or top positioned

---

## 🚀 Deployment Guide

### **Deploy Backend (Laravel on Railway)**
```sh
railway init
railway up
```

### **Deploy Frontend (Netlify/Vercel)**
```sh
cd frontend
npm run build
netlify deploy --prod
```

---

## ✅ Features Implemented
- 🔍 **Post Search** (Keywords, Hashtags, Mentions)
- 🔄 **Infinite Scrolling**
- 🗂 **Filters & Sorting**
- 📱 **Responsive UI with TailwindCSS**

💡 **Contributions & Issues** – Feel free to open an issue or submit a PR!

