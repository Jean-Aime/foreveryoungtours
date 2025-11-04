const mongoose = require('mongoose');

const bookingSchema = new mongoose.Schema({
    bookingId: {
        type: String,
        unique: true,
        required: true
    },
    user: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    tour: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Tour',
        required: true
    },
    travelers: [{
        firstName: {
            type: String,
            required: true
        },
        lastName: {
            type: String,
            required: true
        },
        email: String,
        phone: String,
        dateOfBirth: Date,
        passportNumber: String,
        nationality: String,
        dietaryRequirements: String,
        medicalConditions: String
    }],
    travelDates: {
        startDate: {
            type: Date,
            required: true
        },
        endDate: {
            type: Date,
            required: true
        }
    },
    pricing: {
        basePrice: {
            type: Number,
            required: true
        },
        taxes: {
            type: Number,
            default: 0
        },
        fees: {
            type: Number,
            default: 0
        },
        discount: {
            type: Number,
            default: 0
        },
        totalAmount: {
            type: Number,
            required: true
        }
    },
    status: {
        type: String,
        enum: ['pending', 'confirmed', 'cancelled', 'completed'],
        default: 'pending'
    },
    paymentStatus: {
        type: String,
        enum: ['pending', 'partial', 'paid', 'refunded'],
        default: 'pending'
    },
    specialRequests: String,
    emergencyContact: {
        name: String,
        phone: String,
        relationship: String
    },
    advisor: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    },
    commission: {
        rate: {
            type: Number,
            default: 0.15
        },
        amount: Number
    },
    cancellationReason: String,
    notes: String
}, {
    timestamps: true
});

bookingSchema.pre('save', function(next) {
    if (!this.bookingId) {
        this.bookingId = 'BK' + Date.now() + Math.random().toString(36).substr(2, 5).toUpperCase();
    }
    
    if (this.pricing && !this.commission.amount) {
        this.commission.amount = this.pricing.totalAmount * this.commission.rate;
    }
    
    next();
});

module.exports = mongoose.model('Booking', bookingSchema);