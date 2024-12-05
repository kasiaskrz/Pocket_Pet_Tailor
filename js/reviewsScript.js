// Select DOM elements 
const reviewForm = document.getElementById("review-form");
const reviewsList = document.getElementById("reviews-list");

// Handle form submission
reviewForm.addEventListener("submit", function (event) {
  event.preventDefault();  // Prevent page reload on form submit

  // Get form data
  const name = document.getElementById("name").value;
  const rating = document.getElementById("rating").value;
  const review = document.getElementById("review").value;
  const productId = document.querySelector('input[name="product_id"]').value;

  // send the review data to the backend (submitReview.php) via AJAX
  const formData = new FormData();
  formData.append('username', name);
  formData.append('rating', rating);
  formData.append('review_text', review);
  formData.append('product_id', productId);

  fetch('submitReview.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    if (data === "Review submitted successfully.") {
      // Create a new success message element
      const successMessage = document.createElement("div");
      successMessage.classList.add("success-message");
      successMessage.textContent = "Review was sent successfully.";

      reviewsList.appendChild(successMessage);
    } else {
      alert("Error submitting review: " + data);
    }
  })
  .catch(error => {
    console.error("Error:", error);
    alert("An error occurred while submitting the review.");
  });

  reviewForm.reset();
});
