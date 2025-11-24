# iForYoungTours - Complete Dashboard System

A comprehensive Admin and User Dashboard System for the African tourism platform iForYoungTours, featuring real-time management, analytics, and seamless backend integration.

## 🌟 Features

### Admin Dashboard
- **Analytics & KPIs**: Revenue tracking, booking statistics, user metrics
- **Tour Management**: CRUD operations for tours, packages, and destinations
- **Booking Management**: Real-time booking monitoring and status updates
- **User Management**: User roles, permissions, and account management
- **Partner Management**: Travel partner onboarding and commission tracking
- **Payment Processing**: Stripe integration for secure transactions
- **Content Management**: Dynamic website content updates
- **Real-time Notifications**: Live updates for bookings and system events

### User Dashboard
- **Personal Overview**: Booking history, travel statistics, upcoming trips
- **Booking Management**: View, modify, and cancel reservations
- **Payment History**: Transaction records and invoice downloads
- **Profile Management**: Personal information and travel preferences
- **Wishlist**: Save favorite tours and destinations
- **Support Center**: Help desk and communication tools

## 🏗️ Architecture

### Frontend
- **HTML5 + Tailwind CSS**: Modern, responsive design
- **Vanilla JavaScript**: Lightweight, fast performance
- **ECharts.js**: Interactive data visualization
- **Anime.js**: Smooth animations and transitions
- **Gold-themed Design**: Consistent with brand identity

### Backend
- **Node.js + Express**: RESTful API server
- **MongoDB**: Document database for flexible data storage
- **JWT Authentication**: Secure token-based authentication
- **Stripe Integration**: Payment processing and webhooks
- **Multer**: File upload handling
- **Nodemailer**: Email notifications
- **Socket.io**: Real-time communication

## 📁 Project Structure

```
foreveryoungtours/
├── backend/                    # Backend API
│   ├── config/                # Database and environment config
│   ├── controllers/           # Business logic controllers
│   ├── models/               # MongoDB data models
│   ├── routes/               # API route definitions
│   ├── middleware/           # Authentication and error handling
│   ├── utils/                # Helper utilities
│   ├── server.js             # Main server file
│   ├── package.json          # Dependencies
│   ├── Dockerfile            # Container configuration
│   └── docker-compose.yml    # Multi-service deployment
├── pages/                     # Frontend pages
│   ├── admin-dashboard.html   # Admin interface
│   ├── user-dashboard.html    # User interface
│   └── dashboard.php          # Legacy dashboard (updated)
├── assets/
│   ├── js/
│   │   ├── admin-dashboard.js # Admin functionality
│   │   ├── user-dashboard.js  # User functionality
│   │   └── dashboard-modules.js # Shared components
│   └── css/
│       └── modern-styles.css  # Enhanced styling
└── README-DASHBOARD.md        # This file
```

## 🚀 Quick Start

### Prerequisites
- Node.js 16+ and npm
- MongoDB 6.0+
- Modern web browser

### Backend Setup

1. **Install Dependencies**
```bash
cd backend
npm install
```

2. **Environment Configuration**
```bash
cp .env.example .env
# Edit .env with your configuration
```

3. **Start MongoDB**
```bash
# Using Docker
docker run -d -p 27017:27017 --name mongodb mongo:6.0

# Or use local MongoDB installation
mongod
```

4. **Start Backend Server**
```bash
# Development
npm run dev

# Production
npm start
```

### Frontend Setup

1. **Serve Frontend Files**
```bash
# Using XAMPP (place in htdocs)
# Or use any web server

# Simple Python server for testing
python -m http.server 3000
```

2. **Access Dashboards**
- Admin Dashboard: `http://localhost:3000/pages/admin-dashboard.html`
- User Dashboard: `http://localhost:3000/pages/user-dashboard.html`

## 🔧 Configuration

### Environment Variables (.env)

```env
# Server Configuration
NODE_ENV=development
PORT=5000

# Database
MONGO_URI=mongodb://localhost:27017/iforeveryoungtours

# Authentication
JWT_SECRET=your_super_secret_jwt_key
JWT_EXPIRE=30d

# Email Service
EMAIL_HOST=smtp.gmail.com
EMAIL_PORT=587
EMAIL_USER=your_email@gmail.com
EMAIL_PASS=your_app_password

# Payment Processing
STRIPE_SECRET_KEY=sk_test_your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

# File Storage (Optional)
AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key
AWS_BUCKET_NAME=your_s3_bucket
AWS_REGION=us-east-1

# Frontend URL
CLIENT_URL=http://localhost:3000
```

## 📊 Database Schema

### Core Models

**User Model**
```javascript
{
  name: String,
  email: String (unique),
  password: String (hashed),
  role: ['admin', 'partner', 'traveler', 'advisor'],
  profileImage: String,
  preferences: Object,
  isActive: Boolean,
  timestamps: true
}
```

**Tour Model**
```javascript
{
  title: String,
  description: String,
  destination: {
    country: String,
    city: String,
    region: String
  },
  price: {
    amount: Number,
    currency: String
  },
  duration: {
    days: Number,
    nights: Number
  },
  category: ['safari', 'cultural', 'beach', 'adventure', 'luxury'],
  images: [Object],
  itinerary: [Object],
  partner: ObjectId,
  isActive: Boolean,
  featured: Boolean
}
```

**Booking Model**
```javascript
{
  bookingId: String (unique),
  user: ObjectId,
  tour: ObjectId,
  travelers: [Object],
  travelDates: {
    startDate: Date,
    endDate: Date
  },
  pricing: {
    basePrice: Number,
    taxes: Number,
    fees: Number,
    totalAmount: Number
  },
  status: ['pending', 'confirmed', 'cancelled', 'completed'],
  paymentStatus: ['pending', 'partial', 'paid', 'refunded']
}
```

## 🔌 API Endpoints

### Authentication
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `GET /api/auth/me` - Get current user

### Users
- `GET /api/users/profile` - Get user profile
- `PUT /api/users/profile` - Update user profile
- `GET /api/users/dashboard` - Get dashboard data
- `GET /api/users` - Get all users (Admin)

### Tours
- `GET /api/tours` - Get all tours (with filters)
- `GET /api/tours/:id` - Get single tour
- `POST /api/tours` - Create tour (Admin/Partner)
- `PUT /api/tours/:id` - Update tour (Admin/Partner)
- `DELETE /api/tours/:id` - Delete tour (Admin/Partner)

### Bookings
- `GET /api/bookings` - Get user bookings
- `POST /api/bookings` - Create booking
- `GET /api/bookings/:id` - Get single booking
- `PUT /api/bookings/:id` - Update booking
- `PUT /api/bookings/:id/cancel` - Cancel booking
- `GET /api/bookings/admin/all` - Get all bookings (Admin)

## 🎨 Design System

### Color Palette
- **Primary Gold**: `#DAA520` - Main brand color
- **Secondary Gold**: `#F1C40F` - Accent color
- **Primary Green**: `#228B22` - Success states
- **Neutral Grays**: `#f8f9fa` to `#343a40` - Text and backgrounds

### Typography
- **Font Family**: Poppins (Google Fonts)
- **Weights**: 300, 400, 500, 600, 700, 800

### Components
- **Glass-morphism Cards**: Translucent backgrounds with blur effects
- **Rounded Corners**: 16px-24px border radius for modern look
- **Smooth Animations**: CSS transitions and Anime.js animations
- **Responsive Grid**: CSS Grid and Flexbox layouts

## 🔒 Security Features

### Authentication & Authorization
- JWT token-based authentication
- Role-based access control (RBAC)
- Password hashing with bcrypt
- Token expiration and refresh

### Data Protection
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- CORS configuration
- Rate limiting

### Payment Security
- Stripe PCI compliance
- Webhook signature verification
- Secure payment processing
- Transaction logging

## 📈 Performance Optimization

### Frontend
- Lazy loading for images and components
- CSS and JavaScript minification
- Browser caching strategies
- Responsive image optimization

### Backend
- Database indexing for fast queries
- Connection pooling
- Caching strategies
- Compression middleware

## 🚀 Deployment

### Docker Deployment

1. **Build and Run**
```bash
cd backend
docker-compose up -d
```

2. **Environment Setup**
```bash
# Production environment variables
docker-compose -f docker-compose.prod.yml up -d
```

### AWS Deployment

1. **Elastic Beanstalk**
```bash
# Install EB CLI
pip install awsebcli

# Initialize and deploy
eb init
eb create production
eb deploy
```

2. **Database Setup**
```bash
# Use MongoDB Atlas or AWS DocumentDB
# Update MONGO_URI in environment variables
```

### Manual Deployment

1. **Server Setup**
```bash
# Install Node.js and MongoDB
# Clone repository
# Install dependencies
# Configure environment
# Start with PM2
pm2 start server.js --name "iforeveryoungtours-api"
```

## 🧪 Testing

### Backend Testing
```bash
# Unit tests
npm test

# Integration tests
npm run test:integration

# Coverage report
npm run test:coverage
```

### Frontend Testing
```bash
# Manual testing checklist
# - Dashboard loading
# - API integration
# - User interactions
# - Responsive design
```

## 📚 API Documentation

### Sample Requests

**Create Booking**
```javascript
POST /api/bookings
{
  "tourId": "64a1b2c3d4e5f6789012345",
  "travelDates": {
    "startDate": "2024-08-15",
    "endDate": "2024-08-22"
  },
  "travelers": [
    {
      "firstName": "John",
      "lastName": "Doe",
      "email": "john@example.com",
      "phone": "+1234567890"
    }
  ],
  "specialRequests": "Vegetarian meals",
  "emergencyContact": {
    "name": "Jane Doe",
    "phone": "+1234567891",
    "relationship": "Spouse"
  }
}
```

**Response**
```javascript
{
  "success": true,
  "data": {
    "_id": "64a1b2c3d4e5f6789012346",
    "bookingId": "BK1703123456ABC",
    "user": "64a1b2c3d4e5f6789012344",
    "tour": {...},
    "status": "pending",
    "pricing": {
      "basePrice": 2000,
      "taxes": 200,
      "fees": 50,
      "totalAmount": 2250
    }
  }
}
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

© 2024 iForYoungTours. All rights reserved.

## 🆘 Support

For technical support or questions:
- Email: support@iforeveryoungtours.com
- Documentation: [Link to full docs]
- Issues: [GitHub Issues]

---

**Built with ❤️ for African Tourism**