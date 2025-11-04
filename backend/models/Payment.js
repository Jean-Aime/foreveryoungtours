const mongoose = require('mongoose');

const paymentSchema = new mongoose.Schema({
    booking: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Booking',
        required: true
    },
    user: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    amount: {
        type: Number,
        required: true
    },
    currency: {
        type: String,
        default: 'USD'
    },
    paymentMethod: {
        type: String,
        enum: ['stripe', 'paypal', 'bank_transfer', 'cash'],
        required: true
    },
    transactionId: {
        type: String,
        required: true,
        unique: true
    },
    stripePaymentIntentId: String,
    status: {
        type: String,
        enum: ['pending', 'processing', 'succeeded', 'failed', 'cancelled', 'refunded'],
        default: 'pending'
    },
    paymentType: {
        type: String,
        enum: ['deposit', 'full_payment', 'installment', 'refund'],
        default: 'full_payment'
    },
    metadata: {
        stripeSessionId: String,
        paypalOrderId: String,
        bankReference: String,
        notes: String
    },
    refund: {
        amount: Number,
        reason: String,
        refundId: String,
        processedAt: Date
    },
    processedAt: Date,
    failureReason: String
}, {
    timestamps: true
});

paymentSchema.pre('save', function(next) {
    if (this.status === 'succeeded' && !this.processedAt) {
        this.processedAt = new Date();
    }
    next();
});

module.exports = mongoose.model('Payment', paymentSchema);