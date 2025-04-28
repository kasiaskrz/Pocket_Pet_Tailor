const requiredFields = [
    'nname', 'email', 'city', 'address', 'zip', 'country',
    'sname', 'semail', 'scity', 'saddress', 'szip', 'scountry',
    'cname', 'ccnum', 'expmonth', 'expyear', 'cvv'
];

// Fill countries and expiry fields
document.addEventListener('DOMContentLoaded', () => {
    const countries = ["Ireland", "United Kingdom"];
    document.querySelectorAll('select[name="country"], select[name="scountry"]').forEach(select => {
        countries.forEach(country => {
            const option = document.createElement('option');
            option.value = country;
            option.textContent = country;
            select.appendChild(option);
        });
    });

    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    months.forEach((month, index) => {
        const option = document.createElement('option');
        option.value = index + 1;
        option.textContent = month;
        document.getElementById('expmonth').appendChild(option);
    });

    const year = new Date().getFullYear();
    for (let i = 0; i <= 10; i++) {
        const option = document.createElement('option');
        option.value = year + i;
        option.textContent = year + i;
        document.getElementById('expyear').appendChild(option);
    }

    requiredFields.forEach(id => {
        const field = document.getElementById(id);
        if (field) {
            field.addEventListener('input', validateCheckoutForm);
            field.addEventListener('blur', validateCheckoutForm);
        }
    });

    document.getElementById('same-as-billing').addEventListener('change', repeatAddress);

    validateCheckoutForm();
});

// Copy Billing to Shipping
function repeatAddress() {
    if (document.getElementById('same-as-billing').checked) {
        ['nname', 'email', 'city', 'address', 'zip', 'country'].forEach((field, index) => {
            document.getElementById(['sname', 'semail', 'scity', 'saddress', 'szip', 'scountry'][index]).value = document.getElementById(field).value;
        });
    } else {
        ['sname', 'semail', 'scity', 'saddress', 'szip', 'scountry'].forEach(id => {
            document.getElementById(id).value = '';
        });
    }
    validateCheckoutForm();
}

// Validate whole form
function validateCheckoutForm() {
    let valid = true;

    requiredFields.forEach(id => {
        const field = document.getElementById(id);
        const error = document.getElementById(`error-${id}`);
        if (field && error) {
            if (!field.checkValidity()) {
                valid = false;
                field.classList.add('input-error');
                error.textContent = getErrorMessage(field);
            } else {
                field.classList.remove('input-error');
                error.textContent = '';
            }
        }
    });

    document.getElementById('pay-now-btn').disabled = !valid;
}

// Dynamic error messages
function getErrorMessage(field) {
    if (field.validity.valueMissing) return 'required';
    if (field.validity.patternMismatch) {
        if (field.name === 'zip' || field.name === 'szip') return 'Postcode must be 3-10 characters.';
        if (field.name === 'ccnum') return 'Credit card must be 16 digits.';
        if (field.name === 'cvv') return 'CVV must be 3 or 4 digits.';
    }
    if (field.validity.tooShort) {
        if (field.name.includes('name') || field.name.includes('city')) return 'Minimum 2 letters required.';
        if (field.name.includes('address')) return 'Address must be at least 5 characters.';
    }
    if (field.type === 'email' && field.validity.typeMismatch) {
        return 'Please enter a valid email address.';
    }
    return 'Invalid input.';
}

// Clear form fields
function clearCheckoutForms() {
    document.getElementById('checkout-order-form').reset();
    validateCheckoutForm();
}

document.getElementById('checkout-order-form').addEventListener('submit', function (e) {
    e.preventDefault();

    fetch('save_order.php')    // Save order + clear cart
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'order_success.php';
            } else {
                alert('Something went wrong: ' + (data.message || 'Unknown error.'));
            }
        })
        .catch(error => {
            console.error('Error saving order:', error);
            alert('An error occurred.');
        });
});

