document.getElementById('motor').addEventListener('input', function (e) {
    let motor = e.target.value.replace(/\D/g, '');
    e.target.value = motor ? motor + ' CC' : '';
});

document.getElementById('quilometragem').addEventListener('input', function (e) {
    let quilometragem = e.target.value.replace(/\D/g, '');
    e.target.value = quilometragem ? quilometragem.replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' KM' : '';
});

document.getElementById('imagem').addEventListener('change', function (event) {
    const files = event.target.files;
    const previewContainer = document.querySelector('.preview-container');
    previewContainer.innerHTML = '';
    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('preview-img');
            img.style.maxWidth = '200px';
            img.style.borderRadius = '8px';
            img.style.border = '1px solid #ced4da';
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});