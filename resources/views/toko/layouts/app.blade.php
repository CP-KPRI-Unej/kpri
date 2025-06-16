<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPRI Universitas Jember - Toko Online - @yield('title', 'Katalog Produk')</title>

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite('resources/css/app.css')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body class="font-poppins text-gray-800 antialiased bg-gray-50 dark:bg-gray-900 dark:text-gray-200">
    @include('comprof.partials.navbar')

    <main>
        @yield('content')
    </main>

    <!-- Shopping Cart Modal -->
    <div id="cart-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50" id="cart-backdrop"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 w-full max-w-xl rounded-lg shadow-xl overflow-hidden transform transition-all">
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 p-4">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Keranjang Belanja
                    </h3>
                    <button id="close-cart" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="max-h-96 overflow-y-auto p-4" id="cart-items">
                    <!-- Cart items will be inserted here -->
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400" id="empty-cart-message">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p>Keranjang belanja Anda kosong</p>
                        <a href="{{ route('toko.index') }}" class="text-amber-500 hover:text-amber-600 mt-2 inline-block">Lihat Katalog Produk</a>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 p-4" id="cart-summary">
                    <div class="flex justify-between font-semibold text-gray-800 dark:text-white mb-4">
                        <span>Total:</span>
                        <span id="cart-total">Rp 0</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                        <button id="clear-cart" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-sm font-medium transition flex-1 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Kosongkan
                        </button>
                        <button id="checkout-cart" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md text-sm font-medium transition flex-1 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 1.856.001 3.598.723 4.907 2.034 1.31 1.311 2.031 3.054 2.03 4.908-.001 3.825-3.113 6.938-6.937 6.938z"/>
                            </svg>
                            Checkout via WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('comprof.partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)')
                    .matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            window.toggleDarkMode = function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    
    <!-- Shopping Cart Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cart state
            let cart = [];
            
            // DOM Elements
            const cartModal = document.getElementById('cart-modal');
            const cartBackdrop = document.getElementById('cart-backdrop');
            const closeCartBtn = document.getElementById('close-cart');
            const cartButton = document.getElementById('cart-button');
            const mobileCartButton = document.getElementById('mobile-cart-button');
            const cartBadge = document.getElementById('cart-badge');
            const mobileCartBadge = document.getElementById('mobile-cart-badge');
            const cartItems = document.getElementById('cart-items');
            const emptyCartMessage = document.getElementById('empty-cart-message');
            const cartTotal = document.getElementById('cart-total');
            const clearCartBtn = document.getElementById('clear-cart');
            const checkoutCartBtn = document.getElementById('checkout-cart');
            
            // Initialize cart from localStorage
            initCart();
            
            // Event Listeners
            cartButton.addEventListener('click', toggleCart);
            mobileCartButton.addEventListener('click', toggleCart);
            cartBackdrop.addEventListener('click', closeCart);
            closeCartBtn.addEventListener('click', closeCart);
            clearCartBtn.addEventListener('click', clearCart);
            checkoutCartBtn.addEventListener('click', checkoutCart);
            
            // Custom event for adding to cart
            window.addEventListener('add-to-cart', function(e) {
                addToCart(e.detail);
            });
            
            // Functions
            function initCart() {
                const savedCart = localStorage.getItem('kpri_cart');
                if (savedCart) {
                    cart = JSON.parse(savedCart);
                    updateCartUI();
                }
            }
            
            function toggleCart() {
                cartModal.classList.toggle('hidden');
            }
            
            function closeCart() {
                cartModal.classList.add('hidden');
            }
            
            function addToCart(product) {
                // Check if product already exists in cart
                const existingProductIndex = cart.findIndex(item => item.id === product.id);
                
                if (existingProductIndex !== -1) {
                    // Update quantity if product already exists
                    cart[existingProductIndex].quantity += product.quantity;
                } else {
                    // Add new product to cart
                    cart.push(product);
                }
                
                // Save to localStorage
                saveCart();
                
                // Update UI
                updateCartUI();
                
                // Show cart
                toggleCart();
            }
            
            function removeFromCart(productId) {
                cart = cart.filter(item => item.id !== productId);
                saveCart();
                updateCartUI();
            }
            
            function updateQuantity(productId, quantity) {
                const productIndex = cart.findIndex(item => item.id === productId);
                
                if (productIndex !== -1) {
                    if (quantity <= 0) {
                        removeFromCart(productId);
                    } else {
                        cart[productIndex].quantity = quantity;
                        saveCart();
                        updateCartUI();
                    }
                }
            }
            
            function clearCart() {
                cart = [];
                saveCart();
                updateCartUI();
            }
            
            function saveCart() {
                localStorage.setItem('kpri_cart', JSON.stringify(cart));
            }
            
            function updateCartUI() {
                // Update cart badge
                const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
                
                if (totalItems > 0) {
                    cartBadge.textContent = totalItems;
                    cartBadge.classList.remove('hidden');
                    mobileCartBadge.textContent = totalItems;
                    mobileCartBadge.classList.remove('hidden');
                } else {
                    cartBadge.classList.add('hidden');
                    mobileCartBadge.classList.add('hidden');
                }
                
                // Update cart items
                if (cart.length === 0) {
                    emptyCartMessage.classList.remove('hidden');
                    cartItems.querySelectorAll('.cart-item').forEach(item => item.remove());
                } else {
                    emptyCartMessage.classList.add('hidden');
                    
                    // Clear existing items
                    cartItems.querySelectorAll('.cart-item').forEach(item => item.remove());
                    
                    // Add each item to cart
                    cart.forEach(item => {
                        const cartItemElement = document.createElement('div');
                        cartItemElement.className = 'cart-item flex border-b border-gray-200 dark:border-gray-700 pb-3 mb-3 last:border-0 last:pb-0 last:mb-0';
                        
                        // Format the price
                        const formattedPrice = new Intl.NumberFormat('id-ID').format(item.price);
                        const formattedTotal = new Intl.NumberFormat('id-ID').format(item.price * item.quantity);
                        
                        cartItemElement.innerHTML = `
                            <div class="flex-shrink-0 w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded overflow-hidden mr-3">
                                <img src="${item.image || '/images/no-image.png'}" alt="${item.name}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-medium text-gray-800 dark:text-white text-sm mb-1">${item.name}</h4>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Rp ${formattedPrice}</div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <button class="decrement-qty bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 w-6 h-6 flex items-center justify-center rounded-l-md" data-id="${item.id}">-</button>
                                        <input type="text" class="cart-qty w-10 h-6 text-center border-t border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-sm" value="${item.quantity}" data-id="${item.id}">
                                        <button class="increment-qty bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 w-6 h-6 flex items-center justify-center rounded-r-md" data-id="${item.id}">+</button>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-800 dark:text-white mr-2">
                                            Rp ${formattedTotal}
                                        </div>
                                        <button class="remove-item text-red-500 hover:text-red-700" data-id="${item.id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        cartItems.appendChild(cartItemElement);
                    });
                    
                    // Add event listeners to cart item buttons
                    cartItems.querySelectorAll('.remove-item').forEach(button => {
                        button.addEventListener('click', function() {
                            const productId = parseInt(this.dataset.id);
                            removeFromCart(productId);
                        });
                    });
                    
                    cartItems.querySelectorAll('.increment-qty').forEach(button => {
                        button.addEventListener('click', function() {
                            const productId = parseInt(this.dataset.id);
                            const productIndex = cart.findIndex(item => item.id === productId);
                            
                            if (productIndex !== -1) {
                                updateQuantity(productId, cart[productIndex].quantity + 1);
                            }
                        });
                    });
                    
                    cartItems.querySelectorAll('.decrement-qty').forEach(button => {
                        button.addEventListener('click', function() {
                            const productId = parseInt(this.dataset.id);
                            const productIndex = cart.findIndex(item => item.id === productId);
                            
                            if (productIndex !== -1 && cart[productIndex].quantity > 1) {
                                updateQuantity(productId, cart[productIndex].quantity - 1);
                            } else {
                                removeFromCart(productId);
                            }
                        });
                    });
                    
                    cartItems.querySelectorAll('.cart-qty').forEach(input => {
                        input.addEventListener('change', function() {
                            const productId = parseInt(this.dataset.id);
                            const quantity = parseInt(this.value);
                            
                            if (!isNaN(quantity) && quantity > 0) {
                                updateQuantity(productId, quantity);
                            } else {
                                // Reset to previous value if invalid
                                const productIndex = cart.findIndex(item => item.id === productId);
                                if (productIndex !== -1) {
                                    this.value = cart[productIndex].quantity;
                                }
                            }
                        });
                    });
                }
                
                // Update cart total
                const totalPrice = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                cartTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
            }
            
            function checkoutCart() {
                if (cart.length === 0) return;
                
                // Build WhatsApp message
                let message = 'Halo KPRI UNEJ, saya ingin memesan produk berikut:\n\n';
                
                cart.forEach((item, index) => {
                    const totalPrice = item.price * item.quantity;
                    message += `${index + 1}. ${item.name}\n`;
                    message += `   Harga: Rp ${new Intl.NumberFormat('id-ID').format(item.price)}\n`;
                    message += `   Jumlah: ${item.quantity}\n`;
                    message += `   Subtotal: Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}\n\n`;
                });
                
                const totalPrice = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                message += `Total: Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}\n\n`;
                message += 'Mohon diproses pesanan saya. Terima kasih.';
                
                // Open WhatsApp
                const encodedMessage = encodeURIComponent(message);
                window.open(`https://wa.me/6281234567890?text=${encodedMessage}`, '_blank');
            }
        });
    </script>
    
    @stack('scripts')
</body>

</html> 