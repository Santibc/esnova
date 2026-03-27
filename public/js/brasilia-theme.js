/**
 * BRASILIA THEME - JAVASCRIPT
 * Custom scripts for interactive functionality
 */

document.addEventListener('DOMContentLoaded', function() {

    // ================================
    // HERO SWIPER INITIALIZATION
    // ================================
    const heroSwiper = new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
    });

    // ================================
    // CATEGORIES SWIPER INITIALIZATION
    // ================================
    const categoriesSwiper = new Swiper('.categories-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 20,
        navigation: {
            nextEl: '.categories-btn-next',
            prevEl: '.categories-btn-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 3,
                spaceBetween: 10
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 15
            },
            992: {
                slidesPerView: 6,
                spaceBetween: 20
            },
            1200: {
                slidesPerView: 7,
                spaceBetween: 20
            }
        }
    });

    // ================================
    // PRODUCTS SWIPER INITIALIZATION (4 items)
    // ================================
    const productsSwiper = new Swiper('.products-swiper', {
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: '.products-btn-next',
            prevEl: '.products-btn-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 1.5,
                spaceBetween: 10
            },
            576: {
                slidesPerView: 2,
                spaceBetween: 15
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 15
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 20
            }
        }
    });

    // ================================
    // LO NUEVO SWIPER INITIALIZATION
    // ================================
    const loNuevoSwiper = new Swiper('.lo-nuevo-swiper', {
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: '.lo-nuevo-btn-next',
            prevEl: '.lo-nuevo-btn-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 1.5,
                spaceBetween: 10
            },
            576: {
                slidesPerView: 2,
                spaceBetween: 15
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 15
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 20
            }
        }
    });

    // ================================
    // OFERTAS SWIPER INITIALIZATION
    // ================================
    const ofertasSwiper = new Swiper('.ofertas-swiper', {
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: '.ofertas-btn-next',
            prevEl: '.ofertas-btn-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 1.5,
                spaceBetween: 10
            },
            576: {
                slidesPerView: 2,
                spaceBetween: 15
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 15
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 20
            }
        }
    });

    // ================================
    // ESTILO AUTÉNTICO - IMAGE THUMBNAILS
    // ================================
    const estiloThumbnails = document.querySelectorAll('.estilo-thumbnail');
    const estiloMainImage = document.getElementById('estiloMainImage');

    if (estiloThumbnails.length > 0 && estiloMainImage) {
        estiloThumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Remove active class from all thumbnails
                estiloThumbnails.forEach(t => t.classList.remove('active'));

                // Add active class to clicked thumbnail
                this.classList.add('active');

                // Change main image
                const newImageSrc = this.getAttribute('data-image');
                estiloMainImage.src = newImageSrc;
            });
        });
    }

    // ================================
    // SEARCH FUNCTIONALITY
    // ================================
    const searchInput = document.querySelector('.js-search-input');
    const searchForm = document.querySelector('.js-search-form');
    const emptySearchBtn = document.querySelector('.js-empty-search');
    const searchSuggestions = document.querySelector('.js-search-form-suggestions');

    if (searchInput) {
        // Show empty button when typing
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                if (emptySearchBtn) emptySearchBtn.style.display = 'block';
            } else {
                if (emptySearchBtn) emptySearchBtn.style.display = 'none';
                if (searchSuggestions) searchSuggestions.style.display = 'none';
            }
        });

        // Clear search
        if (emptySearchBtn) {
            emptySearchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                searchInput.value = '';
                this.style.display = 'none';
                if (searchSuggestions) searchSuggestions.style.display = 'none';
                searchInput.focus();
            });
        }

        // Simulate search suggestions (you can connect to real API)
        searchInput.addEventListener('focus', function() {
            if (this.value.length > 2 && searchSuggestions) {
                // searchSuggestions.style.display = 'block';
                // Add your suggestions logic here
            }
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (searchSuggestions && !searchForm.contains(e.target)) {
                searchSuggestions.style.display = 'none';
            }
        });
    }

    // ================================
    // MODAL FUNCTIONALITY
    // ================================
    const modalTriggers = document.querySelectorAll('.js-modal-open-private');

    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const targetSelector = this.getAttribute('data-target');

            if (targetSelector) {
                const targetModal = document.querySelector(targetSelector);

                if (targetModal) {
                    // If it's a Bootstrap modal
                    if (targetModal.classList.contains('modal')) {
                        const bsModal = new bootstrap.Modal(targetModal);
                        bsModal.show();
                    }
                }
            }
        });
    });

    // ================================
    // CART FUNCTIONALITY
    // ================================
    const cartButtons = document.querySelectorAll('.product-card .btn-outline-primary');
    const cartAmount = document.querySelector('.js-cart-widget-amount');
    let cartCount = 0;

    cartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Increment cart count
            cartCount++;
            if (cartAmount) {
                cartAmount.textContent = cartCount;
                cartAmount.style.display = 'flex';
            }

            // Add animation
            this.textContent = '✓ Agregado';
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-success');

            // Reset button after 2 seconds
            setTimeout(() => {
                this.textContent = 'Agregar al carrito';
                this.classList.remove('btn-success');
                this.classList.add('btn-outline-primary');
            }, 2000);

            // Show success message
            showNotification('Producto agregado al carrito', 'success');
        });
    });

    // ================================
    // HEADER SCROLL BEHAVIOR
    // ================================
    let lastScroll = 0;
    const header = document.querySelector('.head-main');

    if (header) {
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;

            // Add shadow when scrolling
            if (currentScroll > 50) {
                header.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            } else {
                header.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.1)';
            }

            lastScroll = currentScroll;
        });
    }

    // ================================
    // NEWSLETTER FORM
    // ================================
    const newsletterForm = document.querySelector('.newsletter-form');

    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value;

            if (email && isValidEmail(email)) {
                showNotification('¡Gracias por suscribirte!', 'success');
                emailInput.value = '';
            } else {
                showNotification('Por favor ingresa un email válido', 'error');
            }
        });
    }

    // ================================
    // PRODUCT VIEW DETAILS
    // ================================
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        const overlay = card.querySelector('.product-overlay');
        if (overlay) {
            const viewBtn = overlay.querySelector('.btn');
            if (viewBtn) {
                viewBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productName = card.querySelector('.product-name').textContent;
                    showNotification(`Viendo detalles de: ${productName}`, 'info');
                });
            }
        }
    });

    // ================================
    // CATEGORY CARDS
    // ================================
    const categoryCards = document.querySelectorAll('.category-card');

    categoryCards.forEach(card => {
        const btn = card.querySelector('.btn');
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const categoryName = card.querySelector('.category-title').textContent;
                showNotification(`Explorando categoría: ${categoryName}`, 'info');
            });
        }
    });

    // ================================
    // MOBILE MENU BEHAVIOR
    // ================================
    const hamburgerModal = document.querySelector('#nav-hamburger');

    if (hamburgerModal) {
        // Close menu when clicking on a link
        const mobileLinks = hamburgerModal.querySelectorAll('.mobile-nav-link');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                const bsModal = bootstrap.Modal.getInstance(hamburgerModal);
                if (bsModal) {
                    bsModal.hide();
                }
            });
        });
    }

    // ================================
    // ADBAR ANIMATIONS
    // ================================
    const adbarAnimated = document.querySelector('.adbar-content-animated');

    if (adbarAnimated) {
        // Clone messages for infinite scroll effect
        const messages = adbarAnimated.innerHTML;
        adbarAnimated.innerHTML += messages;
    }

    // ================================
    // LAZY LOADING IMAGES
    // ================================
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // ================================
    // UTILITY FUNCTIONS
    // ================================

    /**
     * Show notification toast
     */
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotification = document.querySelector('.brasilia-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `brasilia-notification brasilia-notification-${type}`;
        notification.textContent = message;

        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#d4e85c'};
            color: ${type === 'info' ? '#2c3357' : 'white'};
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            font-size: 14px;
            font-weight: 500;
            animation: slideInRight 0.3s ease;
            max-width: 300px;
        `;

        // Add animation keyframes
        if (!document.querySelector('#brasilia-notification-styles')) {
            const style = document.createElement('style');
            style.id = 'brasilia-notification-styles';
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        // Append to body
        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    /**
     * Validate email format
     */
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    /**
     * Format price
     */
    function formatPrice(price) {
        return '$' + price.toLocaleString('es-AR');
    }

    /**
     * Smooth scroll to element
     */
    function smoothScrollTo(element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    // ================================
    // INITIALIZE TOOLTIPS (Bootstrap)
    // ================================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ================================
    // PERFORMANCE OPTIMIZATION
    // ================================

    // Debounce function for scroll events
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Throttle function for resize events
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    console.log('🎨 Brasilia Theme initialized successfully!');
});
