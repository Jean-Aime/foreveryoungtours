const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');
require('dotenv').config();

// Import models
const User = require('../models/User');
const Tour = require('../models/Tour');
const Partner = require('../models/Partner');
const Booking = require('../models/Booking');

// Connect to database
mongoose.connect(process.env.MONGO_URI, {
    useNewUrlParser: true,
    useUnifiedTopology: true,
});

const seedData = async () => {
    try {
        // Clear existing data
        await User.deleteMany({});
        await Tour.deleteMany({});
        await Partner.deleteMany({});
        await Booking.deleteMany({});

        console.log('Cleared existing data');

        // Create sample users
        const hashedPassword = await bcrypt.hash('password123', 10);
        
        const users = await User.create([
            {
                name: 'Admin User',
                email: 'admin@iforeveryoungtours.com',
                password: hashedPassword,
                role: 'admin',
                isActive: true
            },
            {
                name: 'Sarah Johnson',
                email: 'sarah@example.com',
                password: hashedPassword,
                role: 'traveler',
                phone: '+1234567890',
                preferences: {
                    destinations: ['Kenya', 'Tanzania', 'Morocco'],
                    budget: { min: 1000, max: 5000 },
                    travelStyle: 'adventure'
                }
            },
            {
                name: 'Michael Chen',
                email: 'michael@example.com',
                password: hashedPassword,
                role: 'advisor',
                phone: '+1234567891'
            },
            {
                name: 'Emma Rodriguez',
                email: 'emma@safariadventures.com',
                password: hashedPassword,
                role: 'partner',
                phone: '+1234567892'
            }
        ]);

        console.log('Created sample users');

        // Create sample partners
        const partners = await Partner.create([
            {
                name: 'Safari Adventures Ltd',
                company: 'Safari Adventures Ltd',
                email: 'emma@safariadventures.com',
                phone: '+254712345678',
                website: 'https://safariadventures.com',
                description: 'Premier safari operator in East Africa with 15+ years experience',
                address: {
                    street: '123 Safari Street',
                    city: 'Nairobi',
                    country: 'Kenya',
                    zipCode: '00100'
                },
                specialties: ['safari', 'adventure'],
                operatingRegions: ['Kenya', 'Tanzania', 'Uganda'],
                commissionRate: 0.15,
                status: 'approved',
                isActive: true
            },
            {
                name: 'Morocco Desert Tours',
                company: 'Morocco Desert Tours SARL',
                email: 'info@moroccodesert.com',
                phone: '+212612345678',
                website: 'https://moroccodesert.com',
                description: 'Authentic desert experiences and cultural tours in Morocco',
                address: {
                    street: '456 Medina Road',
                    city: 'Marrakech',
                    country: 'Morocco',
                    zipCode: '40000'
                },
                specialties: ['cultural', 'adventure'],
                operatingRegions: ['Morocco'],
                commissionRate: 0.12,
                status: 'approved',
                isActive: true
            }
        ]);

        console.log('Created sample partners');

        // Create sample tours
        const tours = await Tour.create([
            {
                title: 'Kenya Safari Adventure',
                description: 'Experience the Big Five in Kenya\'s most famous national parks. This 7-day safari takes you through Masai Mara, Amboseli, and Tsavo National Parks.',
                shortDescription: 'Ultimate Big Five safari experience in Kenya\'s premier parks',
                destination: {
                    country: 'Kenya',
                    city: 'Nairobi',
                    region: 'East Africa'
                },
                price: {
                    amount: 2499,
                    currency: 'USD'
                },
                duration: {
                    days: 7,
                    nights: 6
                },
                category: 'safari',
                difficulty: 'moderate',
                groupSize: {
                    min: 2,
                    max: 8
                },
                images: [
                    {
                        url: 'https://images.unsplash.com/photo-1516426122078-c23e76319801',
                        caption: 'Lions in Masai Mara',
                        isPrimary: true
                    }
                ],
                itinerary: [
                    {
                        day: 1,
                        title: 'Arrival in Nairobi',
                        description: 'Airport pickup and transfer to hotel',
                        activities: ['Airport transfer', 'Hotel check-in', 'Welcome dinner'],
                        accommodation: 'Nairobi Safari Club',
                        meals: ['Dinner']
                    },
                    {
                        day: 2,
                        title: 'Nairobi to Masai Mara',
                        description: 'Drive to Masai Mara with game drive en route',
                        activities: ['Game drive', 'Masai village visit'],
                        accommodation: 'Mara Safari Lodge',
                        meals: ['Breakfast', 'Lunch', 'Dinner']
                    }
                ],
                includes: [
                    'Airport transfers',
                    'Accommodation',
                    'All meals',
                    'Game drives',
                    'Professional guide',
                    'Park fees'
                ],
                excludes: [
                    'International flights',
                    'Visa fees',
                    'Personal expenses',
                    'Tips and gratuities'
                ],
                availableDates: [
                    {
                        startDate: new Date('2024-08-15'),
                        endDate: new Date('2024-08-21'),
                        availableSpots: 6,
                        price: 2499
                    },
                    {
                        startDate: new Date('2024-09-10'),
                        endDate: new Date('2024-09-16'),
                        availableSpots: 8,
                        price: 2699
                    }
                ],
                partner: partners[0]._id,
                isActive: true,
                featured: true,
                rating: {
                    average: 4.8,
                    count: 24
                }
            },
            {
                title: 'Morocco Desert Expedition',
                description: 'Journey through the Sahara Desert with camel trekking, Berber camps, and ancient kasbahs. Discover the magic of Morocco\'s imperial cities.',
                shortDescription: 'Sahara desert adventure with camel trekking and Berber culture',
                destination: {
                    country: 'Morocco',
                    city: 'Marrakech',
                    region: 'North Africa'
                },
                price: {
                    amount: 1899,
                    currency: 'USD'
                },
                duration: {
                    days: 8,
                    nights: 7
                },
                category: 'cultural',
                difficulty: 'moderate',
                groupSize: {
                    min: 4,
                    max: 12
                },
                images: [
                    {
                        url: 'https://images.unsplash.com/photo-1539650116574-75c0c6d73f6e',
                        caption: 'Sahara Desert Dunes',
                        isPrimary: true
                    }
                ],
                itinerary: [
                    {
                        day: 1,
                        title: 'Arrival in Marrakech',
                        description: 'Explore the vibrant souks and Jemaa el-Fnaa square',
                        activities: ['Medina tour', 'Souk shopping', 'Traditional dinner'],
                        accommodation: 'Riad Marrakech',
                        meals: ['Dinner']
                    }
                ],
                includes: [
                    'Airport transfers',
                    'Accommodation',
                    'Daily breakfast',
                    'Camel trekking',
                    'Desert camping',
                    'Local guide'
                ],
                excludes: [
                    'International flights',
                    'Lunch and dinner (except desert camp)',
                    'Personal expenses',
                    'Optional activities'
                ],
                availableDates: [
                    {
                        startDate: new Date('2024-07-20'),
                        endDate: new Date('2024-07-27'),
                        availableSpots: 10,
                        price: 1899
                    }
                ],
                partner: partners[1]._id,
                isActive: true,
                featured: true,
                rating: {
                    average: 4.6,
                    count: 18
                }
            },
            {
                title: 'Zanzibar Beach Paradise',
                description: 'Relax on pristine white sand beaches, explore Stone Town\'s history, and enjoy world-class snorkeling in crystal clear waters.',
                shortDescription: 'Tropical beach getaway with cultural exploration',
                destination: {
                    country: 'Tanzania',
                    city: 'Zanzibar',
                    region: 'East Africa'
                },
                price: {
                    amount: 1299,
                    currency: 'USD'
                },
                duration: {
                    days: 5,
                    nights: 4
                },
                category: 'beach',
                difficulty: 'easy',
                groupSize: {
                    min: 2,
                    max: 10
                },
                images: [
                    {
                        url: 'https://images.unsplash.com/photo-1544551763-46a013bb70d5',
                        caption: 'Zanzibar Beach',
                        isPrimary: true
                    }
                ],
                partner: partners[0]._id,
                isActive: true,
                featured: false,
                rating: {
                    average: 4.7,
                    count: 31
                }
            }
        ]);

        console.log('Created sample tours');

        // Create sample bookings
        const bookings = await Booking.create([
            {
                user: users[1]._id, // Sarah Johnson
                tour: tours[0]._id,  // Kenya Safari
                travelers: [
                    {
                        firstName: 'Sarah',
                        lastName: 'Johnson',
                        email: 'sarah@example.com',
                        phone: '+1234567890',
                        dateOfBirth: new Date('1990-05-15'),
                        nationality: 'American'
                    },
                    {
                        firstName: 'John',
                        lastName: 'Smith',
                        email: 'john@example.com',
                        phone: '+1234567891',
                        dateOfBirth: new Date('1988-03-22'),
                        nationality: 'American'
                    }
                ],
                travelDates: {
                    startDate: new Date('2024-08-15'),
                    endDate: new Date('2024-08-21')
                },
                pricing: {
                    basePrice: 4998, // 2 travelers
                    taxes: 499.8,
                    fees: 50,
                    totalAmount: 5547.8
                },
                status: 'confirmed',
                paymentStatus: 'paid',
                specialRequests: 'Vegetarian meals for both travelers',
                emergencyContact: {
                    name: 'Mary Johnson',
                    phone: '+1234567892',
                    relationship: 'Mother'
                },
                commission: {
                    rate: 0.15,
                    amount: 832.17
                }
            },
            {
                user: users[1]._id, // Sarah Johnson
                tour: tours[1]._id,  // Morocco Desert
                travelers: [
                    {
                        firstName: 'Sarah',
                        lastName: 'Johnson',
                        email: 'sarah@example.com',
                        phone: '+1234567890',
                        dateOfBirth: new Date('1990-05-15'),
                        nationality: 'American'
                    }
                ],
                travelDates: {
                    startDate: new Date('2024-09-20'),
                    endDate: new Date('2024-09-27')
                },
                pricing: {
                    basePrice: 1899,
                    taxes: 189.9,
                    fees: 50,
                    totalAmount: 2138.9
                },
                status: 'pending',
                paymentStatus: 'pending',
                specialRequests: 'Ground floor accommodation preferred',
                emergencyContact: {
                    name: 'Mary Johnson',
                    phone: '+1234567892',
                    relationship: 'Mother'
                },
                commission: {
                    rate: 0.12,
                    amount: 256.67
                }
            }
        ]);

        console.log('Created sample bookings');

        console.log('✅ Sample data seeded successfully!');
        console.log('\n📊 Created:');
        console.log(`- ${users.length} users`);
        console.log(`- ${partners.length} partners`);
        console.log(`- ${tours.length} tours`);
        console.log(`- ${bookings.length} bookings`);
        
        console.log('\n🔐 Login Credentials:');
        console.log('Admin: admin@iforeveryoungtours.com / password123');
        console.log('User: sarah@example.com / password123');
        console.log('Advisor: michael@example.com / password123');
        console.log('Partner: emma@safariadventures.com / password123');

        process.exit(0);
    } catch (error) {
        console.error('Error seeding data:', error);
        process.exit(1);
    }
};

// Run seeder
seedData();