(function () {
    document.addEventListener('submit', async function (event) {
        const form = event.target.closest('.ucb-consult .consultation-form');
        if (!form) return;
        event.preventDefault();
        const button = form.querySelector('button[type="submit"]');
        const status = form.querySelector('.form-status');
        const original = button.textContent;
        const data = new FormData(form);
        data.append('action', 'unicancer_submit_consultation');
        data.append('nonce', window.unicancerBlockForm.nonce);
        data.append('source_url', window.location.href);
        button.disabled = true;
        button.textContent = form.dataset.submittingText || 'Đang gửi...';
        try {
            const response = await fetch(window.unicancerBlockForm.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.data && result.data.message ? result.data.message : 'Gửi không thành công.');
            status.textContent = result.data.message;
            status.style.color = '#159648';
            form.reset();
        } catch (error) {
            status.textContent = error.message;
            status.style.color = '#dc2626';
        } finally {
            button.disabled = false;
            button.textContent = original;
        }
    });
})();
