/**
 * Lost and Found Portal - Main JavaScript File
 * Handles Image Upload Previews, Form Validations, Dynamic Filters, and Interactive Elements
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log("Lost & Found Portal initialized successfully.");

    // 1. Image Upload Live Preview Handler
    const imageInput = document.getElementById('item_image');
    const imagePreviewContainer = document.getElementById('image_preview_container');
    const imagePreviewElement = document.getElementById('image_preview');

    if (imageInput && imagePreviewContainer && imagePreviewElement) {
        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                // File Type Validation
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, PNG, WEBP).');
                    imageInput.value = '';
                    imagePreviewContainer.classList.add('d-none');
                    return;
                }

                // File Size Validation (Max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Image file size must not exceed 5MB.');
                    imageInput.value = '';
                    imagePreviewContainer.classList.add('d-none');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreviewElement.src = e.target.result;
                    imagePreviewContainer.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                imagePreviewContainer.classList.add('d-none');
            }
        });
    }

    // 2. Initialize Bootstrap Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // 3. Confirm Deletion Handler for Action Buttons
    const deleteButtons = document.querySelectorAll('.confirm-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to delete this report? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // 4. Auto Dismiss Alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 5000);
    });
});
