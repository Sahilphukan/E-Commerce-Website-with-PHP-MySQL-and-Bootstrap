// Add interactivity
document.addEventListener('DOMContentLoaded', function () {
    // Example: Add to cart button
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            alert('Product added to cart!');
        });
    });
});