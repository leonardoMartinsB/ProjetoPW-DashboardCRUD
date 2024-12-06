function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = "block";
    modal.style.opacity = "1";
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.opacity = "0";
    setTimeout(() => {
        modal.style.display = "none";
    }, 300);
}