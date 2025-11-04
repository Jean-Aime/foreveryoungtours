const express = require('express');
const { protect, authorize } = require('../middleware/authMiddleware');
const {
    getTours,
    getTour,
    createTour,
    updateTour,
    deleteTour,
    getFeaturedTours
} = require('../controllers/tourController');

const router = express.Router();

// Public routes
router.get('/', getTours);
router.get('/featured', getFeaturedTours);
router.get('/:id', getTour);

// Protected routes
router.post('/', protect, authorize('admin', 'partner'), createTour);
router.put('/:id', protect, authorize('admin', 'partner'), updateTour);
router.delete('/:id', protect, authorize('admin', 'partner'), deleteTour);

module.exports = router;