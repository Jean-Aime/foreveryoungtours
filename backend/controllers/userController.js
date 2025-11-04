const User = require('../models/User');
const Booking = require('../models/Booking');
const { generateToken } = require('../middleware/authMiddleware');

// @desc    Get user profile
// @route   GET /api/users/profile
// @access  Private
const getUserProfile = async (req, res) => {
    try {
        const user = await User.findById(req.user.id).select('-password');
        res.json({
            success: true,
            data: user
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'Server error'
        });
    }
};

// @desc    Update user profile
// @route   PUT /api/users/profile
// @access  Private
const updateUserProfile = async (req, res) => {
    try {
        const user = await User.findById(req.user.id);

        if (user) {
            user.name = req.body.name || user.name;
            user.email = req.body.email || user.email;
            user.phone = req.body.phone || user.phone;
            user.address = req.body.address || user.address;
            user.preferences = req.body.preferences || user.preferences;

            if (req.body.password) {
                user.password = req.body.password;
            }

            const updatedUser = await user.save();

            res.json({
                success: true,
                data: {
                    _id: updatedUser._id,
                    name: updatedUser.name,
                    email: updatedUser.email,
                    role: updatedUser.role,
                    phone: updatedUser.phone,
                    address: updatedUser.address,
                    preferences: updatedUser.preferences,
                    token: generateToken(updatedUser._id)
                }
            });
        } else {
            res.status(404).json({
                success: false,
                message: 'User not found'
            });
        }
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'Server error'
        });
    }
};

// @desc    Get user dashboard data
// @route   GET /api/users/dashboard
// @access  Private
const getUserDashboard = async (req, res) => {
    try {
        const userId = req.user.id;
        
        // Get user bookings
        const bookings = await Booking.find({ user: userId })
            .populate('tour', 'title destination price images')
            .sort({ createdAt: -1 })
            .limit(10);

        // Calculate statistics
        const totalBookings = await Booking.countDocuments({ user: userId });
        const confirmedBookings = await Booking.countDocuments({ 
            user: userId, 
            status: 'confirmed' 
        });
        const totalSpent = await Booking.aggregate([
            { $match: { user: userId, paymentStatus: 'paid' } },
            { $group: { _id: null, total: { $sum: '$pricing.totalAmount' } } }
        ]);

        const upcomingBookings = await Booking.find({
            user: userId,
            status: 'confirmed',
            'travelDates.startDate': { $gte: new Date() }
        }).populate('tour', 'title destination').sort({ 'travelDates.startDate': 1 });

        res.json({
            success: true,
            data: {
                statistics: {
                    totalBookings,
                    confirmedBookings,
                    totalSpent: totalSpent[0]?.total || 0,
                    upcomingTrips: upcomingBookings.length
                },
                recentBookings: bookings,
                upcomingBookings
            }
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'Server error'
        });
    }
};

// @desc    Get all users (Admin only)
// @route   GET /api/users
// @access  Private/Admin
const getUsers = async (req, res) => {
    try {
        const page = parseInt(req.query.page) || 1;
        const limit = parseInt(req.query.limit) || 10;
        const skip = (page - 1) * limit;

        const users = await User.find({})
            .select('-password')
            .skip(skip)
            .limit(limit)
            .sort({ createdAt: -1 });

        const total = await User.countDocuments();

        res.json({
            success: true,
            data: users,
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

// @desc    Delete user (Admin only)
// @route   DELETE /api/users/:id
// @access  Private/Admin
const deleteUser = async (req, res) => {
    try {
        const user = await User.findById(req.params.id);

        if (user) {
            await user.remove();
            res.json({
                success: true,
                message: 'User removed'
            });
        } else {
            res.status(404).json({
                success: false,
                message: 'User not found'
            });
        }
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'Server error'
        });
    }
};

module.exports = {
    getUserProfile,
    updateUserProfile,
    getUserDashboard,
    getUsers,
    deleteUser
};