import './bootstrap';

/* ======================================================================
   Cart
   ====================================================================== */
window.Cart = {
    add(productId, quantity = 1) {
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity }),
        })
            .then(res => res.json())
            .then(data => {
                if (data.cart_count !== undefined) {
                    document.querySelectorAll('[data-cart-count]').forEach(el => {
                        el.textContent = data.cart_count;
                        el.classList.toggle('hidden', data.cart_count === 0);
                    });
                }
                Cart.showNotification(data.message || 'Added to cart!');
            })
            .catch(() => Cart.showNotification('Could not add to cart', true));
    },

    remove(productId) {
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId }),
        })
            .then(res => res.json())
            .then(data => {
                if (data.cart_count !== undefined) {
                    document.querySelectorAll('[data-cart-count]').forEach(el => {
                        el.textContent = data.cart_count;
                        el.classList.toggle('hidden', data.cart_count === 0);
                    });
                }
                window.location.reload();
            });
    },

    updateQuantity(productId, quantity) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity }),
        })
            .then(res => res.json())
            .then(data => {
                if (data.cart_count !== undefined) {
                    document.querySelectorAll('[data-cart-count]').forEach(el => {
                        el.textContent = data.cart_count;
                        el.classList.toggle('hidden', data.cart_count === 0);
                    });
                }
                window.location.reload();
            });
    },

    showNotification(message, isError = false) {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-6 left-1/2 z-[9999] px-6 py-3.5 rounded-2xl shadow-xl font-display font-semibold text-white transition-all duration-500 transform translate-y-20 opacity-0 ${isError ? 'bg-red-500' : 'bg-kinder-brown-800'}`;
        notification.style.transform = 'translateX(-50%) translateY(20px)';
        notification.style.opacity = '0';

        const icon = isError ? '✕' : '✓';
        notification.innerHTML = `<span class="inline-flex items-center gap-2"><span class="w-5 h-5 rounded-full ${isError ? 'bg-red-400' : 'bg-kinder-500'} flex items-center justify-center text-xs">${icon}</span>${message}</span>`;

        document.body.appendChild(notification);

        requestAnimationFrame(() => {
            notification.style.transform = 'translateX(-50%) translateY(0)';
            notification.style.opacity = '1';
        });

        setTimeout(() => {
            notification.style.transform = 'translateX(-50%) translateY(20px)';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }, 2500);
    },
};

/* ======================================================================
   Wishlist
   ====================================================================== */
window.Wishlist = {
    toggle(productId, button) {
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId }),
        })
            .then(res => {
                if (res.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                return res.json();
            })
            .then(data => {
                if (!data) return;

                if (button) {
                    const icon = button.querySelector('svg');
                    if (data.added) {
                        button.classList.add('!opacity-100', '!text-kinder-500');
                        if (icon) icon.setAttribute('fill', 'currentColor');
                    } else {
                        button.classList.remove('!opacity-100', '!text-kinder-500');
                        if (icon) icon.setAttribute('fill', 'none');
                    }
                }

                document.querySelectorAll('[data-wishlist-count]').forEach(el => {
                    el.textContent = data.wishlist_count;
                    el.classList.toggle('hidden', data.wishlist_count === 0);
                });

                Cart.showNotification(data.message || (data.added ? 'Adaugat la favorite!' : 'Eliminat din favorite'));
            });
    },
};

/* ======================================================================
   Product Filter (catalog page)
   ====================================================================== */
window.ProductFilter = {
    init() {
        const form = document.getElementById('product-filter-form');
        if (!form) return;

        form.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('change', () => form.submit());
        });
    },
};

/* ======================================================================
   Recently Viewed
   ====================================================================== */
window.RecentlyViewed = {
    key: 'kinder_recently_viewed',
    maxItems: 10,

    add(productId) {
        let items = JSON.parse(localStorage.getItem(this.key) || '[]');
        items = items.filter(id => id !== productId);
        items.unshift(productId);
        items = items.slice(0, this.maxItems);
        localStorage.setItem(this.key, JSON.stringify(items));
    },

    get() {
        return JSON.parse(localStorage.getItem(this.key) || '[]');
    },

    clear() {
        localStorage.removeItem(this.key);
    },
};

/* ======================================================================
   Image Gallery (product detail page)
   ====================================================================== */
window.ImageGallery = {
    init(mainImageId, thumbnailsSelector) {
        const mainImage = document.getElementById(mainImageId);
        if (!mainImage) return;

        document.querySelectorAll(thumbnailsSelector).forEach(thumb => {
            thumb.addEventListener('click', function () {
                mainImage.src = this.dataset.src;
                mainImage.alt = this.dataset.alt || '';

                document.querySelectorAll(thumbnailsSelector).forEach(t =>
                    t.classList.remove('ring-2', 'ring-kinder-500')
                );
                this.classList.add('ring-2', 'ring-kinder-500');
            });
        });
    },
};

/* ======================================================================
   Quantity Selector
   ====================================================================== */
window.QuantitySelector = {
    init(containerSelector) {
        document.querySelectorAll(containerSelector).forEach(container => {
            const input = container.querySelector('input[type="number"]');
            const minusBtn = container.querySelector('[data-action="minus"]');
            const plusBtn = container.querySelector('[data-action="plus"]');
            if (!input) return;

            const min = parseInt(input.min) || 1;
            const max = parseInt(input.max) || 99;

            if (minusBtn) {
                minusBtn.addEventListener('click', () => {
                    const val = parseInt(input.value) || min;
                    input.value = Math.max(min, val - 1);
                    input.dispatchEvent(new Event('change'));
                });
            }
            if (plusBtn) {
                plusBtn.addEventListener('click', () => {
                    const val = parseInt(input.value) || min;
                    input.value = Math.min(max, val + 1);
                    input.dispatchEvent(new Event('change'));
                });
            }
        });
    },
};

/* ======================================================================
   Mobile Menu
   ====================================================================== */
window.MobileMenu = {
    init() {
        const toggle = document.getElementById('mobile-menu-toggle');
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-menu-overlay');
        if (!toggle || !menu) return;

        const open = () => {
            menu.classList.remove('translate-x-full');
            menu.classList.add('translate-x-0');
            if (overlay) overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        const close = () => {
            menu.classList.add('translate-x-full');
            menu.classList.remove('translate-x-0');
            if (overlay) overlay.classList.add('hidden');
            document.body.style.overflow = '';
        };

        toggle.addEventListener('click', open);
        if (overlay) overlay.addEventListener('click', close);

        document.getElementById('mobile-menu-close')?.addEventListener('click', close);
    },
};

/* ======================================================================
   Scroll Animations (IntersectionObserver)
   ====================================================================== */
window.ScrollAnimations = {
    init() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        if (!elements.length) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
        );

        elements.forEach((el) => observer.observe(el));
    },
};

/* ======================================================================
   Boot
   ====================================================================== */
document.addEventListener('DOMContentLoaded', () => {
    MobileMenu.init();
    ProductFilter.init();
    QuantitySelector.init('.quantity-selector');
    ScrollAnimations.init();
});
