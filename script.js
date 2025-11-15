let products = [];
let nextId = 1;

function addProduct() {
    const ten = document.getElementById('ten').value;
    const gia = parseFloat(document.getElementById('gia').value);
    const soluong = parseInt(document.getElementById('soluong').value);

    if (!ten || isNaN(gia) || isNaN(soluong)) {
        alert("Vui lòng nhập đầy đủ thông tin hợp lệ!");
        return;
    }

    const product = {
        id: nextId++,
        ten,
        gia,
        soluong,
        thanhTien: gia * soluong
    };
    products.push(product);
    renderTable();
    document.getElementById('productForm').reset();
}

function deleteProduct(id) {
    products = products.filter(p => p.id !== id);
    renderTable();
}

function renderTable() {
    const tbody = document.getElementById('productTable').getElementsByTagName('tbody')[0];
    tbody.innerHTML = '';
    let total = 0;
    products.forEach(p => {
        total += p.thanhTien;
        const row = tbody.insertRow();
        row.insertCell(0).innerText = p.id;
        row.insertCell(1).innerText = p.ten;
        row.insertCell(2).innerText = p.gia.toFixed(2);
        row.insertCell(3).innerText = p.soluong;
        row.insertCell(4).innerText = p.thanhTien.toFixed(2);
        const actionCell = row.insertCell(5);
        const delBtn = document.createElement('button');
        delBtn.innerText = 'Xóa';
        delBtn.onclick = () => deleteProduct(p.id);
        actionCell.appendChild(delBtn);
    });
    document.getElementById('total').innerText = total.toFixed(2);
}
