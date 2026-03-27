/**
 * TEMPLATE LIMA - JAVASCRIPT
 * Funcionalidad interactiva del template
 */

(function() {
    'use strict';

    // ========================================
    // 1. STICKY HEADER
    // ========================================
    function initStickyHeader() {
        const header = document.querySelector('.js-head-main');
        if (!header) return;

        let lastScrollTop = 0;
        const scrollThreshold = 100;

        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > scrollThreshold) {
                header.classList.add('compress');
            } else {
                header.classList.remove('compress');
            }

            lastScrollTop = scrollTop;
        });
    }

    // ========================================
    // 2. MODAL SYSTEM
    // ========================================
    function initModals() {
        const modalToggles = document.querySelectorAll('.js-modal-open');
        const modalCloses = document.querySelectorAll('.js-modal-close');
        const overlay = document.querySelector('.js-modal-overlay');

        // Abrir modales
        modalToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const targetSelector = this.getAttribute('data-toggle');
                const targetModal = document.querySelector(targetSelector);

                if (targetModal && overlay) {
                    targetModal.classList.add('active');
                    overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            });
        });

        // Cerrar modales
        function closeAllModals() {
            const activeModals = document.querySelectorAll('.modal.active');
            activeModals.forEach(function(modal) {
                modal.classList.remove('active');
            });

            if (overlay) {
                overlay.classList.remove('active');
            }
            document.body.style.overflow = '';
        }

        modalCloses.forEach(function(close) {
            close.addEventListener('click', closeAllModals);
        });

        if (overlay) {
            overlay.addEventListener('click', closeAllModals);
        }

        // Cerrar con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAllModals();
            }
        });
    }

    // ========================================
    // 3. SWIPER SLIDERS
    // ========================================
    function initSwipers() {
        // Hero Slider
        if (document.querySelector('.js-hero-slider')) {
            new Swiper('.js-hero-slider', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                speed: 800,
            });
        }

        // Featured Products Slider
        if (document.querySelector('.js-featured-slider')) {
            new Swiper('.js-featured-slider', {
                slidesPerView: 2,
                spaceBetween: 20,
                loop: false,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 25,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                },
            });
        }

        // New Products Slider
        if (document.querySelector('.js-new-products-slider')) {
            new Swiper('.js-new-products-slider', {
                slidesPerView: 2,
                spaceBetween: 20,
                loop: false,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 25,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                },
            });
        }

        // Related Products Slider
        if (document.querySelector('.js-related-slider')) {
            new Swiper('.js-related-slider', {
                slidesPerView: 2,
                spaceBetween: 20,
                loop: false,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 25,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                },
            });
        }

        // Product Gallery - Thumbnails
        if (document.querySelector('.js-product-thumbs')) {
            var thumbsSwiper = new Swiper('.js-product-thumbs', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
                breakpoints: {
                    640: {
                        slidesPerView: 4,
                    },
                    768: {
                        slidesPerView: 5,
                    },
                    1024: {
                        slidesPerView: 4,
                    },
                },
            });

            // Product Gallery - Main
            if (document.querySelector('.js-product-main')) {
                new Swiper('.js-product-main', {
                    spaceBetween: 10,
                    thumbs: {
                        swiper: thumbsSwiper,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            }
        }
    }

    // ========================================
    // 4. QUANTITY SELECTOR
    // ========================================
    function initQuantitySelectors() {
        const quantityContainers = document.querySelectorAll('.quantity-selector, .quantity-controls');

        quantityContainers.forEach(function(container) {
            const decreaseBtn = container.querySelector('.js-qty-decrease, .quantity-btn[data-action="decrease"]');
            const increaseBtn = container.querySelector('.js-qty-increase, .quantity-btn[data-action="increase"]');
            const input = container.querySelector('.js-qty-input, .quantity-input');

            if (!input) return;

            if (decreaseBtn) {
                decreaseBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let value = parseInt(input.value) || 1;
                    if (value > 1) {
                        input.value = value - 1;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            }

            if (increaseBtn) {
                increaseBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let value = parseInt(input.value) || 1;
                    const max = parseInt(input.getAttribute('max')) || 999;
                    if (value < max) {
                        input.value = value + 1;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            }

            // Validar input manual
            input.addEventListener('change', function() {
                let value = parseInt(this.value) || 1;
                const min = parseInt(this.getAttribute('min')) || 1;
                const max = parseInt(this.getAttribute('max')) || 999;

                if (value < min) value = min;
                if (value > max) value = max;

                this.value = value;
            });
        });
    }

    // ========================================
    // 5. VARIANT SELECTOR
    // ========================================
    function initVariantSelectors() {
        const variantOptions = document.querySelectorAll('.variant-option');

        variantOptions.forEach(function(option) {
            option.addEventListener('click', function() {
                if (this.classList.contains('disabled')) return;

                // Deseleccionar otros del mismo grupo
                const group = this.closest('.variant-group');
                if (group) {
                    const siblings = group.querySelectorAll('.variant-option');
                    siblings.forEach(function(sibling) {
                        sibling.classList.remove('selected');
                    });
                }

                // Seleccionar este
                this.classList.add('selected');

                // Actualizar input hidden si existe
                const input = this.querySelector('input[type="radio"]');
                if (input) {
                    input.checked = true;
                }

                // Trigger change event para actualizar stock/precio si es necesario
                if (input) {
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });
    }

    // ========================================
    // 6. CARRITO (ADD TO CART)
    // ========================================
    function initCartFunctions() {
        const addToCartForms = document.querySelectorAll('.js-add-to-cart-form');

        addToCartForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const url = form.getAttribute('action');

                // Deshabilitar botón mientras se procesa
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn ? submitBtn.textContent : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Agregando...';
                }

                // Enviar petición AJAX
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        // Actualizar badge del carrito
                        updateCartBadge(data.cart_count);

                        // Mostrar mensaje de éxito
                        showCartNotification('Producto agregado al carrito');

                        // Abrir modal del carrito si existe
                        const cartModal = document.querySelector('#modal-cart');
                        const overlay = document.querySelector('.js-modal-overlay');
                        if (cartModal && overlay) {
                            // Actualizar contenido del carrito si hay HTML
                            if (data.cart_html) {
                                const cartBody = cartModal.querySelector('.modal-body');
                                if (cartBody) {
                                    cartBody.innerHTML = data.cart_html;
                                }
                            }

                            cartModal.classList.add('active');
                            overlay.classList.add('active');
                            document.body.style.overflow = 'hidden';
                        }
                    } else {
                        showCartNotification(data.message || 'Error al agregar producto', 'error');
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    showCartNotification('Error al agregar producto', 'error');
                })
                .finally(function() {
                    // Restaurar botón
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                });
            });
        });

        // Remover item del carrito
        document.addEventListener('click', function(e) {
            if (e.target.closest('.js-cart-remove')) {
                e.preventDefault();
                const btn = e.target.closest('.js-cart-remove');
                const url = btn.getAttribute('href') || btn.getAttribute('data-url');

                if (confirm('¿Eliminar este producto del carrito?')) {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        if (data.success) {
                            updateCartBadge(data.cart_count);

                            // Recargar modal del carrito
                            if (data.cart_html) {
                                const cartBody = document.querySelector('#modal-cart .modal-body');
                                if (cartBody) {
                                    cartBody.innerHTML = data.cart_html;
                                }
                            }
                        }
                    });
                }
            }
        });
    }

    function updateCartBadge(count) {
        const badges = document.querySelectorAll('.cart-badge, .js-cart-count');
        badges.forEach(function(badge) {
            badge.textContent = count;
            if (count > 0) {
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        });
    }

    function showCartNotification(message, type) {
        type = type || 'success';

        // Crear notificación
        const notification = document.createElement('div');
        notification.className = 'cart-notification cart-notification-' + type;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: white;
            padding: 15px 25px;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 9999;
            animation: slideInRight 0.3s ease;
        `;

        document.body.appendChild(notification);

        // Remover después de 3 segundos
        setTimeout(function() {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // ========================================
    // 7. SEARCH AUTOCOMPLETE
    // ========================================
    function initSearchAutocomplete() {
        const searchInput = document.querySelector('.js-search-input');
        if (!searchInput) return;

        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 3) {
                hideSearchResults();
                return;
            }

            searchTimeout = setTimeout(function() {
                // Aquí puedes implementar búsqueda AJAX si lo necesitas
                // Por ahora solo mostramos resultados básicos
            }, 300);
        });

        function hideSearchResults() {
            const resultsContainer = document.querySelector('.search-results');
            if (resultsContainer) {
                resultsContainer.style.display = 'none';
            }
        }
    }

    // ========================================
    // 8. LAZY LOADING IMAGES
    // ========================================
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const lazyImages = document.querySelectorAll('img[data-src]');

            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.getAttribute('data-src');
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

    // ========================================
    // 9. FORM VALIDATION
    // ========================================
    function initFormValidation() {
        const forms = document.querySelectorAll('.js-validate-form');

        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    showCartNotification('Por favor completa todos los campos requeridos', 'error');
                }
            });
        });
    }

    // ========================================
    // 10. NEWSLETTER FORM
    // ========================================
    function initNewsletterForm() {
        const newsletterForm = document.querySelector('.js-newsletter-form');
        if (!newsletterForm) return;

        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const emailInput = this.querySelector('input[type="email"]');
            const submitBtn = this.querySelector('button[type="submit"]');
            const email = emailInput.value.trim();

            if (!email) {
                showCartNotification('Por favor ingresa tu email', 'error');
                return;
            }

            // Deshabilitar botón
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enviando...';

            // Aquí puedes hacer la petición AJAX al servidor
            // Por ahora simulamos una respuesta exitosa
            setTimeout(function() {
                showCartNotification('¡Gracias por suscribirte!', 'success');
                emailInput.value = '';
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }, 1000);
        });
    }

    // ========================================
    // 11. SMOOTH SCROLL
    // ========================================
    function initSmoothScroll() {
        const scrollLinks = document.querySelectorAll('a[href^="#"]');

        scrollLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '#!') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    const headerHeight = document.querySelector('.head-main')?.offsetHeight || 0;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // ========================================
    // INICIALIZACIÓN
    // ========================================
    function init() {
        console.log('Lima Theme: Inicializando...');

        initStickyHeader();
        initModals();

        // Esperar a que Swiper esté disponible
        if (typeof Swiper !== 'undefined') {
            initSwipers();
        } else {
            console.warn('Swiper no está disponible');
        }

        initQuantitySelectors();
        initVariantSelectors();
        initCartFunctions();
        initSearchAutocomplete();
        initLazyLoading();
        initFormValidation();
        initNewsletterForm();
        initSmoothScroll();

        console.log('Lima Theme: Inicialización completa');
    }

    // Ejecutar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
