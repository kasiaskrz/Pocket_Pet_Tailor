// Select DOM elements 
const reviewForm = document.getElementById("review-form");

// Handle form submission
reviewForm.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent page reload on form submit

    // Get form data
    const name = document.getElementById("name").value;
    const rating = document.getElementById("rating").value;
    const review = document.getElementById("review").value;
    const productId = document.querySelector('input[name="product_id"]').value;

    const formData = new FormData();
    formData.append('username', name);
    formData.append('rating', rating);
    formData.append('review_text', review);
    formData.append('product_id', productId);

    fetch('submitReview.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);  // Show success message
            reviewForm.reset();  // Reset form after successful submission
        } else {
            alert("Error submitting review: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while submitting the review.");
    });
});
