/**
 * ============================================
 * BROWSER COMPATIBILITY & POLYFILLS
 * ForeverYoung Tours - Universal JavaScript Support
 * ============================================
 */

(function() {
    'use strict';

    // ============================================
    // 1. BROWSER DETECTION
    // ============================================
    
    const BrowserDetect = {
        isIE: function() {
            return navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > -1;
        },
        isEdge: function() {
            return navigator.userAgent.indexOf('Edge') !== -1;
        },
        isChrome: function() {
            return navigator.userAgent.indexOf('Chrome') !== -1 && !this.isEdge();
        },
        isSafari: function() {
            return navigator.userAgent.indexOf('Safari') !== -1 && !this.isChrome() && !this.isEdge();
        },
        isFirefox: function() {
            return navigator.userAgent.indexOf('Firefox') !== -1;
        },
        isOpera: function() {
            return navigator.userAgent.indexOf('Opera') !== -1 || navigator.userAgent.indexOf('OPR') !== -1;
        },
        isMobile: function() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        },
        isIOS: function() {
            return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        },
        isAndroid: function() {
            return /Android/.test(navigator.userAgent);
        },
        isTouchDevice: function() {
            return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
        }
    };

    // Add browser classes to HTML element
    const html = document.documentElement;
    if (BrowserDetect.isIE()) html.classList.add('is-ie');
    if (BrowserDetect.isEdge()) html.classList.add('is-edge');
    if (BrowserDetect.isChrome()) html.classList.add('is-chrome');
    if (BrowserDetect.isSafari()) html.classList.add('is-safari');
    if (BrowserDetect.isFirefox()) html.classList.add('is-firefox');
    if (BrowserDetect.isMobile()) html.classList.add('is-mobile');
    if (BrowserDetect.isIOS()) html.classList.add('is-ios');
    if (BrowserDetect.isAndroid()) html.classList.add('is-android');
    if (BrowserDetect.isTouchDevice()) html.classList.add('is-touch');

    // ============================================
    // 2. POLYFILLS
    // ============================================

    // Array.from polyfill
    if (!Array.from) {
        Array.from = function(arrayLike) {
            return Array.prototype.slice.call(arrayLike);
        };
    }

    // Array.prototype.forEach polyfill
    if (!Array.prototype.forEach) {
        Array.prototype.forEach = function(callback, thisArg) {
            if (this == null) {
                throw new TypeError('Array.prototype.forEach called on null or undefined');
            }
            var T, k;
            var O = Object(this);
            var len = O.length >>> 0;
            if (typeof callback !== 'function') {
                throw new TypeError(callback + ' is not a function');
            }
            if (arguments.length > 1) {
                T = thisArg;
            }
            k = 0;
            while (k < len) {
                var kValue;
                if (k in O) {
                    kValue = O[k];
                    callback.call(T, kValue, k, O);
                }
                k++;
            }
        };
    }

    // Object.assign polyfill
    if (typeof Object.assign !== 'function') {
        Object.assign = function(target) {
            if (target == null) {
                throw new TypeError('Cannot convert undefined or null to object');
            }
            var to = Object(target);
            for (var index = 1; index < arguments.length; index++) {
                var nextSource = arguments[index];
                if (nextSource != null) {
                    for (var nextKey in nextSource) {
                        if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                            to[nextKey] = nextSource[nextKey];
                        }
                    }
                }
            }
            return to;
        };
    }

    // Element.closest polyfill
    if (!Element.prototype.closest) {
        Element.prototype.closest = function(s) {
            var el = this;
            do {
                if (Element.prototype.matches.call(el, s)) return el;
                el = el.parentElement || el.parentNode;
            } while (el !== null && el.nodeType === 1);
            return null;
        };
    }

    // Element.matches polyfill
    if (!Element.prototype.matches) {
        Element.prototype.matches = Element.prototype.msMatchesSelector || 
                                    Element.prototype.webkitMatchesSelector;
    }

    // CustomEvent polyfill
    if (typeof window.CustomEvent !== 'function') {
        function CustomEvent(event, params) {
            params = params || { bubbles: false, cancelable: false, detail: null };
            var evt = document.createEvent('CustomEvent');
            evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
            return evt;
        }
        window.CustomEvent = CustomEvent;
    }

    // requestAnimationFrame polyfill
    (function() {
        var lastTime = 0;
        var vendors = ['webkit', 'moz'];
        for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
            window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
            window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] || 
                                         window[vendors[x] + 'CancelRequestAnimationFrame'];
        }

        if (!window.requestAnimationFrame) {
            window.requestAnimationFrame = function(callback) {
                var currTime = new Date().getTime();
                var timeToCall = Math.max(0, 16 - (currTime - lastTime));
                var id = window.setTimeout(function() {
                    callback(currTime + timeToCall);
                }, timeToCall);
                lastTime = currTime + timeToCall;
                return id;
            };
        }

        if (!window.cancelAnimationFrame) {
            window.cancelAnimationFrame = function(id) {
                clearTimeout(id);
            };
        }
    })();

    // ============================================
    // 3. VIEWPORT HEIGHT FIX (Mobile Browsers)
    // ============================================

    function setViewportHeight() {
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', vh + 'px');
    }

    setViewportHeight();
    window.addEventListener('resize', setViewportHeight);
    window.addEventListener('orientationchange', setViewportHeight);

    // ============================================
    // 4. SMOOTH SCROLL POLYFILL
    // ============================================

    if (!('scrollBehavior' in document.documentElement.style)) {
        // Import smooth scroll polyfill for older browsers
        var smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
        smoothScrollLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                var targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                var targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    var targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
                    var startPosition = window.pageYOffset;
                    var distance = targetPosition - startPosition;
                    var duration = 800;
                    var start = null;

                    function animation(currentTime) {
                        if (start === null) start = currentTime;
                        var timeElapsed = currentTime - start;
                        var run = ease(timeElapsed, startPosition, distance, duration);
                        window.scrollTo(0, run);
                        if (timeElapsed < duration) requestAnimationFrame(animation);
                    }

                    function ease(t, b, c, d) {
                        t /= d / 2;
                        if (t < 1) return c / 2 * t * t + b;
                        t--;
                        return -c / 2 * (t * (t - 2) - 1) + b;
                    }

                    requestAnimationFrame(animation);
                }
            });
        });
    }

    // ============================================
    // 5. INTERSECTION OBSERVER POLYFILL
    // ============================================

    if (!('IntersectionObserver' in window)) {
        // Simple fallback - show all elements immediately
        var lazyElements = document.querySelectorAll('[data-lazy]');
        lazyElements.forEach(function(element) {
            element.classList.add('visible');
        });
    }

    // ============================================
    // 6. PASSIVE EVENT LISTENERS
    // ============================================

    var supportsPassive = false;
    try {
        var opts = Object.defineProperty({}, 'passive', {
            get: function() {
                supportsPassive = true;
            }
        });
        window.addEventListener('testPassive', null, opts);
        window.removeEventListener('testPassive', null, opts);
    } catch (e) {}

    window.passiveSupported = supportsPassive;

    // ============================================
    // 7. TOUCH EVENT HANDLERS
    // ============================================

    if (BrowserDetect.isTouchDevice()) {
        // Add touch-specific event handlers
        document.addEventListener('touchstart', function() {}, 
            window.passiveSupported ? { passive: true } : false);
        
        // Prevent double-tap zoom on buttons
        var buttons = document.querySelectorAll('button, .btn, a');
        buttons.forEach(function(button) {
            button.addEventListener('touchend', function(e) {
                e.preventDefault();
                this.click();
            }, false);
        });
    }

    // ============================================
    // 8. IOS SAFARI FIXES
    // ============================================

    if (BrowserDetect.isIOS()) {
        // Fix for iOS Safari 100vh issue
        document.body.style.minHeight = window.innerHeight + 'px';
        
        window.addEventListener('resize', function() {
            document.body.style.minHeight = window.innerHeight + 'px';
        });

        // Prevent elastic scrolling
        document.addEventListener('touchmove', function(e) {
            if (e.target.closest('.scroll-container')) {
                return;
            }
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            var scrollHeight = document.documentElement.scrollHeight;
            var clientHeight = document.documentElement.clientHeight;
            
            if (scrollTop === 0 && e.touches[0].clientY > 0) {
                e.preventDefault();
            } else if (scrollTop + clientHeight >= scrollHeight && e.touches[0].clientY < 0) {
                e.preventDefault();
            }
        }, window.passiveSupported ? { passive: false } : false);
    }

    // ============================================
    // 9. VIDEO AUTOPLAY FIX
    // ============================================

    function enableVideoAutoplay() {
        var videos = document.querySelectorAll('video[autoplay]');
        videos.forEach(function(video) {
            video.muted = true;
            video.play().catch(function(error) {
                console.log('Video autoplay failed:', error);
                // Show fallback image if video fails
                var fallback = video.nextElementSibling;
                if (fallback && fallback.id === 'videoFallback') {
                    fallback.classList.remove('hidden');
                    video.style.display = 'none';
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', enableVideoAutoplay);
    } else {
        enableVideoAutoplay();
    }

    // ============================================
    // 10. IMAGE LAZY LOADING FALLBACK
    // ============================================

    if (!('loading' in HTMLImageElement.prototype)) {
        // Lazy loading not supported, load all images immediately
        var lazyImages = document.querySelectorAll('img[loading="lazy"]');
        lazyImages.forEach(function(img) {
            img.src = img.dataset.src || img.src;
        });
    }

    // ============================================
    // 11. FOCUS VISIBLE POLYFILL
    // ============================================

    function handleFirstTab(e) {
        if (e.keyCode === 9) {
            document.body.classList.add('user-is-tabbing');
            window.removeEventListener('keydown', handleFirstTab);
            window.addEventListener('mousedown', handleMouseDownOnce);
        }
    }

    function handleMouseDownOnce() {
        document.body.classList.remove('user-is-tabbing');
        window.removeEventListener('mousedown', handleMouseDownOnce);
        window.addEventListener('keydown', handleFirstTab);
    }

    window.addEventListener('keydown', handleFirstTab);

    // ============================================
    // 12. FORM VALIDATION ENHANCEMENT
    // ============================================

    function enhanceFormValidation() {
        var forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                var inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
                var isValid = true;
                
                inputs.forEach(function(input) {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('error');
                        input.addEventListener('input', function() {
                            this.classList.remove('error');
                        }, { once: true });
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    var firstError = form.querySelector('.error');
                    if (firstError) {
                        firstError.focus();
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', enhanceFormValidation);
    } else {
        enhanceFormValidation();
    }

    // ============================================
    // 13. PERFORMANCE MONITORING
    // ============================================

    if ('PerformanceObserver' in window) {
        try {
            var perfObserver = new PerformanceObserver(function(list) {
                list.getEntries().forEach(function(entry) {
                    if (entry.duration > 50) {
                        console.warn('Long task detected:', entry.name, entry.duration + 'ms');
                    }
                });
            });
            perfObserver.observe({ entryTypes: ['measure', 'navigation'] });
        } catch (e) {
            console.log('Performance monitoring not available');
        }
    }

    // ============================================
    // 14. NETWORK STATUS DETECTION
    // ============================================

    function updateOnlineStatus() {
        var status = navigator.onLine ? 'online' : 'offline';
        document.body.classList.toggle('is-offline', !navigator.onLine);
        
        if (!navigator.onLine) {
            console.warn('You are currently offline');
            // Show offline notification if needed
        }
    }

    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    updateOnlineStatus();

    // ============================================
    // 15. CONSOLE PROTECTION (Production)
    // ============================================

    if (window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
        // Disable console in production
        if (!window.console) window.console = {};
        var methods = ['log', 'debug', 'warn', 'info'];
        for (var i = 0; i < methods.length; i++) {
            console[methods[i]] = function() {};
        }
    }

    // ============================================
    // 16. ORIENTATION CHANGE HANDLER
    // ============================================

    function handleOrientationChange() {
        var orientation = window.orientation || (window.innerWidth > window.innerHeight ? 90 : 0);
        document.body.classList.toggle('landscape', Math.abs(orientation) === 90);
        document.body.classList.toggle('portrait', orientation === 0);
    }

    window.addEventListener('orientationchange', handleOrientationChange);
    handleOrientationChange();

    // ============================================
    // 17. PREVENT ZOOM ON INPUT FOCUS (iOS)
    // ============================================

    if (BrowserDetect.isIOS()) {
        var inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(function(input) {
            var fontSize = window.getComputedStyle(input).fontSize;
            if (parseFloat(fontSize) < 16) {
                input.style.fontSize = '16px';
            }
        });
    }

    // ============================================
    // 18. BACK BUTTON CACHE FIX
    // ============================================

    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });

    // ============================================
    // 19. EXPOSE UTILITIES GLOBALLY
    // ============================================

    window.BrowserCompat = {
        detect: BrowserDetect,
        setViewportHeight: setViewportHeight,
        enableVideoAutoplay: enableVideoAutoplay
    };

    // ============================================
    // 20. INITIALIZATION COMPLETE
    // ============================================

    console.log('Browser Compatibility Module Loaded');
    console.log('Browser:', {
        isIE: BrowserDetect.isIE(),
        isEdge: BrowserDetect.isEdge(),
        isChrome: BrowserDetect.isChrome(),
        isSafari: BrowserDetect.isSafari(),
        isFirefox: BrowserDetect.isFirefox(),
        isMobile: BrowserDetect.isMobile(),
        isTouch: BrowserDetect.isTouchDevice()
    });

})();
