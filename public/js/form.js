// =============================================
// form.js — Form Helper & Validation Feedback
// =============================================

export function setButtonLoading(btnElement, isLoading, loadingText = 'Memproses...') {
    if (!btnElement) return;
    if (isLoading) {
        btnElement.dataset.originalText = btnElement.innerHTML;
        btnElement.disabled = true;
        btnElement.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${loadingText}`;
    } else {
        btnElement.disabled = false;
        btnElement.innerHTML = btnElement.dataset.originalText || btnElement.innerHTML;
    }
}

export function displayValidationErrors(formElement, errors) {
    // Clear existing feedback
    formElement.querySelectorAll('.form-feedback').forEach(el => el.remove());
    formElement.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    if (!errors) return;

    Object.keys(errors).forEach(field => {
        const input = formElement.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'form-feedback';
            feedback.innerText = errors[field][0];
            input.parentNode.appendChild(feedback);
        }
    });
}
