function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('clienteTableBody');
    const tr = table.getElementsByTagName('tr');

    for (let i = 0; i < tr.length; i++) {
        const tdNome = tr[i].getElementsByTagName('td')[1];
        const tdEmail = tr[i].getElementsByTagName('td')[2];
        if (tdNome || tdEmail) {
            const txtValueNome = tdNome.textContent || tdNome.innerText;
            const txtValueEmail = tdEmail.textContent || tdEmail.innerText;
            if (txtValueNome.toLowerCase().indexOf(filter) > -1 || txtValueEmail.toLowerCase().indexOf(filter) > -1) {
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
    window.location.href = 'deletecliente.php?id=' + deleteId;
});

window.onload = function () {
    const alert = document.getElementById('alert');
    if (alert) {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    }
};