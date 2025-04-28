document.addEventListener("DOMContentLoaded", () => {
    // Open Modal for login
    const openModalBtn = document.getElementById("openModal");
    if (openModalBtn) {
        openModalBtn.addEventListener("click", () => {
            document.getElementById("loginModal").style.display = "block";
            document.getElementById("modalOverlay").style.display = "block";
            document.getElementById("loginFormContainer").style.display = "block";
            document.getElementById("registerFormContainer").style.display = "none";
        });
    }

    // Close Modal
    const closeModalBtn = document.querySelector(".close");
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () => {
            document.getElementById("loginModal").style.display = "none";
            document.getElementById("modalOverlay").style.display = "none";
        });
    }

    // Toggle to Register Form
    const showRegisterLink = document.getElementById("showRegister");
    if (showRegisterLink) {
        showRegisterLink.addEventListener("click", (event) => {
            event.preventDefault();
            document.getElementById("loginFormContainer").style.display = "none";
            document.getElementById("registerFormContainer").style.display = "block";
        });
    }

    // Toggle to Login Form
    const showLoginLink = document.getElementById("showLogin");
    if (showLoginLink) {
        showLoginLink.addEventListener("click", (event) => {
            event.preventDefault();
            document.getElementById("registerFormContainer").style.display = "none";
            document.getElementById("loginFormContainer").style.display = "block";
        });
    }

    // Close Modal by clicking on overlay
    window.addEventListener("click", (event) => {
        if (event.target === document.getElementById("modalOverlay")) {
            document.getElementById("loginModal").style.display = "none";
            document.getElementById("modalOverlay").style.display = "none";
        }
    });

    // Submit login form
    window.loginUser = function (event) {
        event.preventDefault();
        const formData = new FormData(document.getElementById('loginForm'));

        fetch('login.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    document.getElementById("loginModal").style.display = "none";
                    document.getElementById("modalOverlay").style.display = "none";
                    window.location.href = "home.php";
                }
            })
            .catch(error => console.error('Error:', error));
    };

    // Submit register form
    window.registerUser = function (event) {
        event.preventDefault();
        const formData = new FormData(document.getElementById('registerForm'));

        fetch('register.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    document.getElementById("loginFormContainer").style.display = "block";
                    document.getElementById("registerFormContainer").style.display = "none";
                }
            })
            .catch(error => console.error('Error:', error));
    };

    // Filter Form Submission
    window.submitFilterForm = function () {
        const form = document.getElementById('product_filter_form');
        const formData = new FormData(form);

        fetch('filterProducts.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                document.getElementById('product-container').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    };

    // Add To Cart Function
    window.addtoCart = function (productId) {
        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    updateCartCount();
                } else {
                    showToast("Error: " + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    };

    // Fetch and display cart item count
    function updateCartCount() {
        fetch('cartCount.php')
            .then(response => response.json())
            .then(data => {
                const countElement = document.getElementById('cart-count');
                if (countElement) {
                    if (data.count > 0) {
                        countElement.textContent = data.count;
                        countElement.style.display = 'inline-block';
                    } else {
                        countElement.textContent = '';
                        countElement.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error fetching cart count:', error));
    }

    function showToast(message) {
        let toast = document.createElement('div');
        toast.className = 'toast-message';
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }

    // Initial triggers on page load
    submitFilterForm();
    updateCartCount();
});
