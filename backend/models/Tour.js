const mongoose = require('mongoose');

const tourSchema = new mongoose.Schema({
    title: {
        type: String,
        required: [true, 'Tour title is required'],
        trim: true
    },
    description: {
        type: String,
        required: [true, 'Tour description is required']
    },
    shortDescription: {
        type: String,
        maxlength: 200
    },
    destination: {
        country: {
            type: String,
            required: true
        },
        city: String,
        region: String
    },
    price: {
        amount: {
            type: Number,
            required: [true, 'Price is required']
        },
        currency: {
            type: String,
            default: 'USD'
        }
    },
    duration: {
        days: {
            type: Number,
            required: true
        },
        nights: {
            type: Number,
            required: true
        }
    },
    category: {
        type: String,
        enum: ['safari', 'cultural', 'beach', 'adventure', 'luxury'],
        required: true
    },
    difficulty: {
        type: String,
        enum: ['easy', 'moderate', 'challenging'],
        default: 'moderate'
    },
    groupSize: {
        min: {
            type: Number,
            default: 2
        },
        max: {
            type: Number,
            default: 12
        }
    },
    images: [{
        url: String,
        caption: String,
        isPrimary: {
            type: Boolean,
            default: false
        }
    }],
    itinerary: [{
        day: Number,
        title: String,
        description: String,
        activities: [String],
        accommodation: String,
        meals: [String]
    }],
    includes: [String],
    excludes: [String],
    availableDates: [{
        startDate: Date,
        endDate: Date,
        availableSpots: Number,
        price: Number
    }],
    partner: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Partner',
        required: true
    },
    isActive: {
        type: Boolean,
        default: true
    },
    featured: {
        type: Boolean,
        default: false
    },
    rating: {
        average: {
            type: Number,
            default: 0
        },
        count: {
            type: Number,
            default: 0
        }
    }
}, {
    timestamps: true
});

tourSchema.index({ 'destination.country': 1, category: 1, price: 1 });

module.exports = mongoose.model('Tour', tourSchema);