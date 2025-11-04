const mongoose = require('mongoose');

const reviewSchema = new mongoose.Schema({
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
    booking: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Booking',
        required: true
    },
    rating: {
        overall: {
            type: Number,
            required: true,
            min: 1,
            max: 5
        },
        accommodation: {
            type: Number,
            min: 1,
            max: 5
        },
        transportation: {
            type: Number,
            min: 1,
            max: 5
        },
        guide: {
            type: Number,
            min: 1,
            max: 5
        },
        value: {
            type: Number,
            min: 1,
            max: 5
        }
    },
    title: {
        type: String,
        required: true,
        maxlength: 100
    },
    comment: {
        type: String,
        required: true,
        maxlength: 1000
    },
    images: [String],
    isVerified: {
        type: Boolean,
        default: false
    },
    isPublished: {
        type: Boolean,
        default: true
    },
    helpfulVotes: {
        type: Number,
        default: 0
    },
    response: {
        text: String,
        respondedBy: {
            type: mongoose.Schema.Types.ObjectId,
            ref: 'User'
        },
        respondedAt: Date
    }
}, {
    timestamps: true
});

reviewSchema.index({ tour: 1, user: 1 }, { unique: true });

module.exports = mongoose.model('Review', reviewSchema);