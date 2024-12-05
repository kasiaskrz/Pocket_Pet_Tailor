document.addEventListener('DOMContentLoaded', () => {
    const filters = document.querySelectorAll('#filters select, #filters input');
    const productContainer = document.getElementById('product-container');

    filters.forEach(filter => {
        filter.addEventListener('change', () => {
            const animalType = document.getElementById('animal_type').value;
            const color = document.getElementById('color').value;
            const material = document.getElementById('material').value;
            const priceFrom = document.getElementById('price_from').value;
            const priceTo = document.getElementById('price_to').value;

            // Send the filters via POST request
            fetch('filterProducts.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `animal_type=${animalType}&color=${color}&material=${material}&price_from=${priceFrom}&price_to=${priceTo}`,
            })
                .then(response => response.text())
                .then(data => {
                    productContainer.innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });
    });
});
