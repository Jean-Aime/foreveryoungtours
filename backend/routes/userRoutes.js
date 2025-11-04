const express = require('express');
const { protect, authorize } = require('../middleware/authMiddleware');
const {
    getUserProfile,
    updateUserProfile,
    getUserDashboard,
    getUsers,
    deleteUser
} = require('../controllers/userController');

const router = express.Router();

// User profile routes
router.route('/profile')
    .get(protect, getUserProfile)
    .put(protect, updateUserProfile);

// User dashboard
router.get('/dashboard', protect, getUserDashboard);

// Admin routes
router.route('/')
    .get(protect, authorize('admin'), getUsers);

router.route('/:id')
    .delete(protect, authorize('admin'), deleteUser);

module.exports = router;