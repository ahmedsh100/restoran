/* ===================================
   RESTORAN - Premium UI JavaScript
   =================================== */

(function() {
    'use strict';

    /* --- Toast Notification System --- */
    const Toast = {
        container: null,

        init() {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);

            this.processFlashMessages();
        },

        show(message, type = 'info', duration = 4000) {
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };

            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <i class="fas ${icons[type] || icons.info} toast-icon"></i>
                <div class="toast-body">${message}</div>
                <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
                <div class="toast-progress"></div>
            `;

            this.container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.add('show');
            });

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }, duration);
        },

        processFlashMessages() {
            const alerts = document.querySelectorAll('.alert-success, .alert-danger, .alert-warning, .alert-info');
            alerts.forEach(alert => {
                const typeMap = {
                    'alert-success': 'success',
                    'alert-danger': 'error',
                    'alert-warning': 'warning',
                    'alert-info': 'info'
                };
                const type = Object.keys(typeMap).find(cls => alert.classList.contains(cls));
                if (type) {
                    this.show(alert.textContent.trim(), typeMap[type]);
                    alert.style.display = 'none';
                }
            });
        }
    };

    /* --- Loading Overlay --- */
    const Loading = {
        overlay: null,

        init() {
            this.overlay = document.createElement('div');
            this.overlay.className = 'loading-overlay';
            this.overlay.innerHTML = '<div class="loading-spinner"></div>';
            document.body.appendChild(this.overlay);
        },

        show() {
            this.overlay.classList.add('active');
        },

        hide() {
            this.overlay.classList.remove('active');
        }
    };

    /* --- Navbar Scroll Effect --- */
    function initNavbarScroll() {
        const navbar = document.querySelector('.navbar');
        if (!navbar) return;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    /* --- Form Submission with Loading --- */
    function initFormLoading() {
        document.querySelectorAll('form[data-loading]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!this.checkValidity()) return;

                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    btn.classList.add('loading');
                    btn.disabled = true;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Processing...';

                    const restoreBtn = () => {
                        btn.classList.remove('loading');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    };

                    setTimeout(restoreBtn, 3000);
                }
            });
        });
    }

    /* --- Image Fallback --- */
    function initImageFallback() {
        document.querySelectorAll('img[data-fallback]').forEach(img => {
            img.addEventListener('error', function() {
                const fallback = this.getAttribute('data-fallback');
                if (fallback) {
                    this.src = fallback;
                } else {
                    this.style.display = 'none';
                    const placeholder = document.createElement('div');
                    placeholder.className = 'img-fallback';
                    placeholder.style.width = this.width || '100%';
                    placeholder.style.height = this.height || '200px';
                    placeholder.innerHTML = '<i class="fas fa-image"></i>';
                    this.parentNode.replaceChild(placeholder, this);
                }
            });
        });
    }

    /* --- Cart Animation --- */
    function initCartAnimation() {
        document.querySelectorAll('form[action*="cart/add"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Adding...';
                    btn.disabled = true;

                    setTimeout(() => {
                        btn.innerHTML = '<i class="fas fa-check"></i> Added!';
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-success');

                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                            btn.disabled = false;
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-primary');
                        }, 1500);
                    }, 800);
                }
            });
        });
    }

    /* --- Payment Method Selection --- */
    function initPaymentSelection() {
        document.querySelectorAll('.payment-option input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.closest('.payment-option').classList.add('selected');
            });
        });

        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change'));
                }
            });
        });
    }

    /* --- Star Rating --- */
    function initStarRating() {
        document.querySelectorAll('.star-rating').forEach(container => {
            const stars = container.querySelectorAll('.rating-star');
            const input = container.querySelector('input[type="hidden"]');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.dataset.value);
                    input.value = value;
                    stars.forEach((s, i) => {
                        s.className = i < value
                            ? 'fas fa-star rating-star'
                            : 'far fa-star rating-star';
                    });
                });

                star.addEventListener('mouseenter', function() {
                    const value = parseInt(this.dataset.value);
                    stars.forEach((s, i) => {
                        s.style.color = i < value ? '#ffc107' : '#ddd';
                    });
                });
            });

            container.addEventListener('mouseleave', function() {
                const currentValue = parseInt(input.value);
                stars.forEach((s, i) => {
                    s.className = i < currentValue
                        ? 'fas fa-star rating-star'
                        : 'far fa-star rating-star';
                    s.style.color = '';
                });
            });
        });
    }

    /* --- Smooth Scroll --- */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    /* --- Initialize Everything --- */
    document.addEventListener('DOMContentLoaded', function() {
        Toast.init();
        Loading.init();
        initNavbarScroll();
        initFormLoading();
        initImageFallback();
        initCartAnimation();
        initPaymentSelection();
        initStarRating();
        initSmoothScroll();

        window.Toast = Toast;
        window.Loading = Loading;
    });

})();
