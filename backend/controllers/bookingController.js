const Booking = require('../models/Booking');
const Tour = require('../models/Tour');
const User = require('../models/User');

// @desc    Create booking
// @route   POST /api/bookings
// @access  Private
const createBooking = async (req, res) => {
    try {
        const { tourId, travelDates, travelers, specialRequests, emergencyContact } = req.body;

        // Verify tour exists
        const tour = await Tour.findById(tourId);
        if (!tour) {
            return res.status(404).json({
                success: false,
                message: 'Tour not found'
            });
        }

        // Calculate pricing
        const basePrice = tour.price.amount * travelers.length;
        const taxes = basePrice * 0.1; // 10% tax
        const fees = 50; // Processing fee
        const totalAmount = basePrice + taxes + fees;

        const booking = await Booking.create({
            user: req.user.id,
            tour: tourId,
            travelers,
            travelDates,
            pricing: {
                basePrice,
                taxes,
                fees,
                totalAmount
            },
            specialRequests,
            emergencyContact
        });

        await booking.populate('tour', 'title destination price');
        await booking.populate('user', 'name email');

        res.status(201).json({
            success: true,
            data: booking
        });
    } catch (error) {
        res.status(400).json({
            success: false,
            message: error.message
        });
    }
};

// @desc    Get user bookings
// @route   GET /api/bookings
// @access  Private
const getUserBookings = async (req, res) => {
    try {
        const page = parseInt(req.query.page) || 1;
        const limit = parseInt(req.query.limit) || 10;
        const skip = (page - 1) * limit;

        let query = { user: req.user.id };
        
        if (req.query.status) {
            query.status = req.query.status;
        }

        const bookings = await Booking.find(query)
            .populate('tour', 'title destination price images duration')
            .skip(skip)
            .limit(limit)
            .sort({ createdAt: -1 });

        const total = await Booking.countDocuments(query);

        res.json({
            success: true,
            data: bookings,
            pagination: {
                page,
                limit,
                total,
                pages: Math.ceil(total / limit)
            }
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'Server error'
        });
    }
};

// @desc    Get single booking
// @route   GET /api/bookings/:id
// @access  Private
const getBooking = async (req, res) => {
    try {
        const booking = await Booking.findById(req.params.id)
            .populate('tour')
            .populate('user', 'name email phone')
            .populate('advisor', 'name email');

        if (!booking) {
            return res.status(404).json({
                success: false,
                message: 'Booking not found'
            });
        }

        // Check if user owns this booking or is admin/advisor
        if (booking.user._id.toString() !== req.user.id && 
            req.user.role !== 'admin' && 
            req.user.role !== 'advisor') {
            return res.status(403).json({
                success: false,
                message: 'Not authorized to view this booking'
            });
        }

        res.json({
            success: true,
            data: booking
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'Server error'
        });
    }
};

// @desc    Update booking
// @route   PUT /api/bookings/:id
// @access  Private
const updateBooking = async (req, res) => {
    try {
        let booking = await Booking.findById(req.params.id);

        if (!booking) {
            return res.status(404).json({
                success: false,
                message: 'Booking not found'
            });
        }

        // Check authorization
        if (booking.user.toString() !== req.user.id && 
            req.user.role !== 'admin' && 
            req.user.role !== 'advisor') {
            return res.status(403).json({
                success: false,
                message: 'Not authorized to update this booking'
            });
        }

        // Only allow certain fields to be updated by users
        const allowedUpdates = ['specialRequests', 'emergencyContact'];
        const updates = {};
        
        if (req.user.role === 'admin' || req.user.role === 'advisor') {
            // Admins and advisors can update more fields
            Object.keys(req.body).forEach(key => {
                updates[key] = req.body[key];
            });
        } else {
            // Regular users can only update limited fields
            allowedUpdates.forEach(field => {
                if (req.body[field] !== undefined) {
                    updates[field] = req.body[field];
                }
            });
        }

        booking = await Booking.findByIdAndUpdate(req.params.id, updates, {
            new: true,
            runValidators: true
        }).populate('tour').populate('user', 'name email');

        res.json({
            success: true,
            data: booking
        });
    } catch (error) {
        res.status(400).json({
            success: false,
            message: error.message
        });
    }
};

// @desc    Cancel booking
// @route   PUT /api/bookings/:id/cancel
// @access  Private
const cancelBooking = async (req, res) => {
    try {
        const booking = await Booking.findById(req.params.id);

        if (!booking) {
            return res.status(404).json({
                success: false,
                message: 'Booking not found'
            });
        }

        // Check authorization
        if (booking.user.toString() !== req.user.id && req.user.role !== 'admin') {
            return res.status(403).json({
                success: false,
                message: 'Not authorized to cancel this booking'
            });
        }

        // Check if booking can be cancelled
        if (booking.status === 'cancelled' || booking.status === 'completed') {
            return res.status(400).json({
                success: false,
                message: 'Booking cannot be cancelled'
            });
        }

        booking.status = 'cancelled';
        booking.cancellationReason = req.body.reason || 'Cancelled by user';
        await booking.save();

        res.json({
            success: true,
            data: booking
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'Server error'
        });
    }
};

// @desc    Get all bookings (Admin only)
// @route   GET /api/bookings/admin/all
// @access  Private/Admin
const getAllBookings = async (req, res) => {
    try {
        const page = parseInt(req.query.page) || 1;
        const limit = parseInt(req.query.limit) || 20;
        const skip = (page - 1) * limit;

        let query = {};
        
        if (req.query.status) {
            query.status = req.query.status;
        }
        
        if (req.query.paymentStatus) {
            query.paymentStatus = req.query.paymentStatus;
        }

        const bookings = await Booking.find(query)
            .populate('tour', 'title destination price')
            .populate('user', 'name email')
            .populate('advisor', 'name email')
            .skip(skip)
            .limit(limit)
            .sort({ createdAt: -1 });

        const total = await Booking.countDocuments(query);

        // Calculate statistics
        const stats = await Booking.aggregate([
            {
                $group: {
                    _id: null,
                    totalBookings: { $sum: 1 },
                    totalRevenue: { $sum: '$pricing.totalAmount' },
                    confirmedBookings: {
                        $sum: { $cond: [{ $eq: ['$status', 'confirmed'] }, 1, 0] }
                    },
                    pendingBookings: {
                        $sum: { $cond: [{ $eq: ['$status', 'pending'] }, 1, 0] }
                    }
                }
            }
        ]);

        res.json({
            success: true,
            data: bookings,
            statistics: stats[0] || {
                totalBookings: 0,
                totalRevenue: 0,
                confirmedBookings: 0,
                pendingBookings: 0
            },
            pagination: {
                page,
                limit,
                total,
                pages: Math.ceil(total / limit)
            }
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'Server error'
        });
    }
};

module.exports = {
    createBooking,
    getUserBookings,
    getBooking,
    updateBooking,
    cancelBooking,
    getAllBookings
};