// style.js
document.addEventListener("DOMContentLoaded", function() {
    // Highlight hàng khi hover
    const rows = document.querySelectorAll(".product-table tr");
    rows.forEach((row, index) => {
        if (index === 0) return; // bỏ header
        row.addEventListener("mouseenter", () => {
            row.style.backgroundColor = "#f0f8ff";
        });
        row.addEventListener("mouseleave", () => {
            row.style.backgroundColor = "";
        });
    });

    // Confirm xóa nâng cao
    const deleteLinks = document.querySelectorAll(".delete-btn");
    deleteLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            const confirmed = confirm("⚠️ Bạn có chắc muốn xóa sản phẩm này?");
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });
});
