const express = require('express');
const { protect, authorize } = require('../middleware/authMiddleware');
const {
    createBooking,
    getUserBookings,
    getBooking,
    updateBooking,
    cancelBooking,
    getAllBookings
} = require('../controllers/bookingController');

const router = express.Router();

// User booking routes
router.route('/')
    .get(protect, getUserBookings)
    .post(protect, createBooking);

router.route('/:id')
    .get(protect, getBooking)
    .put(protect, updateBooking);

router.put('/:id/cancel', protect, cancelBooking);

// Admin routes
router.get('/admin/all', protect, authorize('admin', 'advisor'), getAllBookings);

module.exports = router;