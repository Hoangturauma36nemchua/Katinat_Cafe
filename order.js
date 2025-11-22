// order.js
document.addEventListener('DOMContentLoaded', function() {
    const checks = Array.from(document.querySelectorAll('.check'));
    const qtys = Array.from(document.querySelectorAll('.qty'));
    const totalEl = document.getElementById('total');
    const totalInput = document.getElementById('TotalPrice');

    function calcTotal() {
        let total = 0;
        document.querySelectorAll('.product-item').forEach(item => {
            const check = item.querySelector('.check');
            const qty = item.querySelector('.qty');
            const price = parseFloat(check.dataset.price) || 0;
            const q = parseInt(qty.value) || 0;
            if (check.checked && q > 0) {
                total += price * q;
            }
        });
        totalEl.textContent = total.toLocaleString();
        totalInput.value = total;
    }

    // khi check/uncheck: bật/tắt input qty
    checks.forEach((check) => {
        check.addEventListener('change', (e) => {
            const id = check.dataset.id;
            const qty = document.querySelector(`input.qty[name="qty[${id}]"]`);
            if (qty) qty.disabled = !check.checked;
            calcTotal();
        });
    });

    // khi thay qty
    qtys.forEach(q => q.addEventListener('input', calcTotal));

    // initial calc (nếu có default)
    calcTotal();
});
