# iForYoungTours Platform - Design System

## Design Philosophy

### Visual Language
- **Clean Hub Architecture**: Inspired by Nextcloud's modular approach with clear content blocks and intuitive navigation
- **Professional Travel Aesthetic**: Sophisticated yet approachable design that builds trust for international travel
- **Content-First Design**: Prioritizing travel imagery and information hierarchy over decorative elements
- **Accessible Excellence**: WCAG 2.2 AA compliance ensuring usability for all travelers and advisors

### Color Palette
- **Primary**: Deep Ocean Blue (#1e3a8a) - Trust, reliability, professional travel expertise
- **Secondary**: Warm Terracotta (#dc2626) - African heritage, warmth, adventure
- **Accent**: Golden Sand (#f59e0b) - Luxury, premium experiences, success
- **Neutral**: Charcoal Gray (#374151) - Professional text, UI elements
- **Background**: Clean White (#ffffff) with subtle warm undertones
- **Success**: Forest Green (#059669) - Confirmations, positive actions
- **Warning**: Amber (#d97706) - Alerts, important information

### Typography
- **Display Font**: Tiempos Headline - Bold, editorial presence for hero sections and major headings
- **Body Font**: Inter - Clean, highly readable sans-serif for all interface text and content
- **Monospace**: JetBrains Mono - Technical elements, code, data displays

### Layout Principles
- **Grid System**: 12-column responsive grid with consistent 24px gutters
- **Spacing Scale**: 8px base unit (8, 16, 24, 32, 48, 64, 96px)
- **Content Width**: Maximum 1200px centered with 32px side padding
- **Card-Based Design**: Modular content blocks with consistent shadows and borders

## Visual Effects & Styling

### Used Libraries
- **Anime.js**: Smooth micro-interactions and state transitions
- **ECharts.js**: Data visualization for analytics and booking trends
- **Splide.js**: Image carousels and testimonial sliders
- **Typed.js**: Dynamic text effects for hero sections
- **p5.js**: Subtle background patterns and creative elements
- **Splitting.js**: Advanced text animations and reveals

### Animation Strategy
- **Subtle Motion**: Gentle fade-ins and slide-ups for content reveals
- **Interactive Feedback**: Hover states with 3D tilt and shadow expansion
- **Loading States**: Skeleton screens and progressive image loading
- **Scroll Animations**: Parallax effects limited to decorative elements only

### Header Effects
- **Hero Background**: Subtle particle system using p5.js with travel-themed elements
- **Text Reveals**: Typewriter effect for main value proposition
- **Image Treatment**: Ken Burns effect on hero images with overlay text
- **Navigation**: Smooth transitions with backdrop blur effects

### Component Styling
- **Cards**: Soft shadows with hover lift effects and border radius
- **Buttons**: Gradient backgrounds with smooth state transitions
- **Forms**: Clean input styling with floating labels and validation states
- **Data Tables**: Zebra striping with sortable headers and expandable rows

### Background Design
- **Consistent Base**: Clean white background throughout all pages
- **Decorative Elements**: Subtle geometric patterns in footer and side areas
- **Section Differentiation**: Light gray backgrounds for dashboard sections
- **No Gradients**: Maintaining clean, professional appearance

### Interactive Elements
- **Hover States**: 3D tilt effects on cards, color morphing on buttons
- **Focus Indicators**: Clear accessibility-compliant focus rings
- **Loading States**: Spinner animations and skeleton screens
- **Micro-interactions**: Button press feedback and form validation

### Mobile Considerations
- **Touch Targets**: Minimum 44px touch areas for all interactive elements
- **Gesture Support**: Swipe navigation for image galleries and mobile menus
- **Responsive Typography**: Fluid scaling based on viewport size
- **Performance**: Optimized animations for mobile devices

### Accessibility Features
- **High Contrast**: 4.5:1 minimum contrast ratio for all text
- **Keyboard Navigation**: Full keyboard accessibility for all interactions
- **Screen Reader Support**: Proper ARIA labels and semantic HTML
- **Motion Preferences**: Respect for reduced motion system preferences