function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('motoTableBody');
    const tr = table.getElementsByTagName('tr');

    for (let i = 0; i < tr.length; i++) {
        const tdMarca = tr[i].getElementsByTagName('td')[1];
        const tdModelo = tr[i].getElementsByTagName('td')[2];
        if (tdMarca || tdModelo) {
            const txtValueMarca = tdMarca.textContent || tdMarca.innerText;
            const txtValueModelo = tdModelo.textContent || tdModelo.innerText;
            if (txtValueMarca.toLowerCase().indexOf(filter) > -1 || txtValueModelo.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

let deleteId;

function openDeleteModal(button) {
    deleteId = button.getAttribute('data-id');
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.getElementById('confirmDelete').addEventListener('click', function () {
    window.location.href = 'deletemoto.php?id=' + deleteId;
});

window.onload = function () {
    const alert = document.getElementById('alert');
    if (alert) {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    }
};
