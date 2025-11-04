const mongoose = require('mongoose');

const partnerSchema = new mongoose.Schema({
    name: {
        type: String,
        required: [true, 'Partner name is required'],
        trim: true
    },
    company: {
        type: String,
        required: [true, 'Company name is required']
    },
    email: {
        type: String,
        required: [true, 'Email is required'],
        unique: true,
        lowercase: true
    },
    phone: String,
    website: String,
    logo: String,
    description: String,
    address: {
        street: String,
        city: String,
        country: String,
        zipCode: String
    },
    specialties: [{
        type: String,
        enum: ['safari', 'cultural', 'beach', 'adventure', 'luxury']
    }],
    operatingRegions: [String],
    commissionRate: {
        type: Number,
        default: 0.15,
        min: 0,
        max: 1
    },
    status: {
        type: String,
        enum: ['pending', 'approved', 'suspended', 'rejected'],
        default: 'pending'
    },
    documents: [{
        type: {
            type: String,
            enum: ['license', 'insurance', 'certification', 'other']
        },
        url: String,
        name: String,
        uploadedAt: {
            type: Date,
            default: Date.now
        }
    }],
    bankDetails: {
        accountName: String,
        accountNumber: String,
        bankName: String,
        routingNumber: String,
        swiftCode: String
    },
    statistics: {
        totalBookings: {
            type: Number,
            default: 0
        },
        totalRevenue: {
            type: Number,
            default: 0
        },
        averageRating: {
            type: Number,
            default: 0
        },
        completedTours: {
            type: Number,
            default: 0
        }
    },
    isActive: {
        type: Boolean,
        default: true
    },
    joinedAt: {
        type: Date,
        default: Date.now
    }
}, {
    timestamps: true
});

module.exports = mongoose.model('Partner', partnerSchema);