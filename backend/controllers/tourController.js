const Tour = require('../models/Tour');
const Partner = require('../models/Partner');

// @desc    Get all tours
// @route   GET /api/tours
// @access  Public
const getTours = async (req, res) => {
    try {
        const page = parseInt(req.query.page) || 1;
        const limit = parseInt(req.query.limit) || 12;
        const skip = (page - 1) * limit;

        // Build query
        let query = { isActive: true };
        
        if (req.query.category) {
            query.category = req.query.category;
        }
        
        if (req.query.country) {
            query['destination.country'] = new RegExp(req.query.country, 'i');
        }
        
        if (req.query.minPrice || req.query.maxPrice) {
            query['price.amount'] = {};
            if (req.query.minPrice) query['price.amount'].$gte = parseInt(req.query.minPrice);
            if (req.query.maxPrice) query['price.amount'].$lte = parseInt(req.query.maxPrice);
        }

        if (req.query.search) {
            query.$or = [
                { title: new RegExp(req.query.search, 'i') },
                { description: new RegExp(req.query.search, 'i') },
                { 'destination.country': new RegExp(req.query.search, 'i') }
            ];
        }

        // Sort options
        let sortBy = { createdAt: -1 };
        if (req.query.sort === 'price_low') sortBy = { 'price.amount': 1 };
        if (req.query.sort === 'price_high') sortBy = { 'price.amount': -1 };
        if (req.query.sort === 'rating') sortBy = { 'rating.average': -1 };
        if (req.query.sort === 'duration') sortBy = { 'duration.days': 1 };

        const tours = await Tour.find(query)
            .populate('partner', 'name company')
            .skip(skip)
            .limit(limit)
            .sort(sortBy);

        const total = await Tour.countDocuments(query);

        res.json({
            success: true,
            data: tours,
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

// @desc    Get single tour
// @route   GET /api/tours/:id
// @access  Public
const getTour = async (req, res) => {
    try {
        const tour = await Tour.findById(req.params.id)
            .populate('partner', 'name company email phone website');

        if (!tour) {
            return res.status(404).json({
                success: false,
                message: 'Tour not found'
            });
        }

        res.json({
            success: true,
            data: tour
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'Server error'
        });
    }
};

// @desc    Create tour
// @route   POST /api/tours
// @access  Private/Admin/Partner
const createTour = async (req, res) => {
    try {
        // Set partner based on user role
        if (req.user.role === 'partner') {
            const partner = await Partner.findOne({ email: req.user.email });
            if (!partner) {
                return res.status(400).json({
                    success: false,
                    message: 'Partner profile not found'
                });
            }
            req.body.partner = partner._id;
        }

        const tour = await Tour.create(req.body);

        res.status(201).json({
            success: true,
            data: tour
        });
    } catch (error) {
        res.status(400).json({
            success: false,
            message: error.message
        });
    }
};

// @desc    Update tour
// @route   PUT /api/tours/:id
// @access  Private/Admin/Partner
const updateTour = async (req, res) => {
    try {
        let tour = await Tour.findById(req.params.id);

        if (!tour) {
            return res.status(404).json({
                success: false,
                message: 'Tour not found'
            });
        }

        // Check ownership for partners
        if (req.user.role === 'partner') {
            const partner = await Partner.findOne({ email: req.user.email });
            if (!partner || tour.partner.toString() !== partner._id.toString()) {
                return res.status(403).json({
                    success: false,
                    message: 'Not authorized to update this tour'
                });
            }
        }

        tour = await Tour.findByIdAndUpdate(req.params.id, req.body, {
            new: true,
            runValidators: true
        });

        res.json({
            success: true,
            data: tour
        });
    } catch (error) {
        res.status(400).json({
            success: false,
            message: error.message
        });
    }
};

// @desc    Delete tour
// @route   DELETE /api/tours/:id
// @access  Private/Admin/Partner
const deleteTour = async (req, res) => {
    try {
        const tour = await Tour.findById(req.params.id);

        if (!tour) {
            return res.status(404).json({
                success: false,
                message: 'Tour not found'
            });
        }

        // Check ownership for partners
        if (req.user.role === 'partner') {
            const partner = await Partner.findOne({ email: req.user.email });
            if (!partner || tour.partner.toString() !== partner._id.toString()) {
                return res.status(403).json({
                    success: false,
                    message: 'Not authorized to delete this tour'
                });
            }
        }

        await tour.remove();

        res.json({
            success: true,
            message: 'Tour removed'
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'Server error'
        });
    }
};

// @desc    Get featured tours
// @route   GET /api/tours/featured
// @access  Public
const getFeaturedTours = async (req, res) => {
    try {
        const tours = await Tour.find({ featured: true, isActive: true })
            .populate('partner', 'name company')
            .limit(6)
            .sort({ 'rating.average': -1 });

        res.json({
            success: true,
            data: tours
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'Server error'
        });
    }
};

module.exports = {
    getTours,
    getTour,
    createTour,
    updateTour,
    deleteTour,
    getFeaturedTours
};