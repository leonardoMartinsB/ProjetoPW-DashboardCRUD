document.getElementById('imagem').addEventListener('change', function (event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('preview-container');
    previewContainer.innerHTML = '';
    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('preview-img');
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});

document.getElementById('clear-button').addEventListener('click', function () {
    const previewContainer = document.getElementById('preview-container');
    previewContainer.innerHTML = '';
});

document.getElementById('motor').addEventListener('input', function (e) {
    let motor = e.target.value.replace(/\D/g, '');
    if (motor.length > 4) {
        motor = motor.substring(0, 4);
    }
    e.target.value = motor ? motor + ' CC' : '';
});

document.getElementById('quilometragem').addEventListener('input', function (e) {
    let quilometragem = e.target.value.replace(/\D/g, '');
    if (quilometragem.length > 7) {
        quilometragem = quilometragem.substring(0, 7);
    }
    e.target.value = quilometragem ? quilometragem.replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' KM' : '';
});

document.getElementById('motor').addEventListener('focus', function (e) {
    let value = e.target.value.replace(' CC', '');
    e.target.value = value;
});

document.getElementById('motor').addEventListener('blur', function (e) {
    let motor = e.target.value.replace(/\D/g, '');
    if (motor) {
        e.target.value = motor + ' CC';
    }
});

document.getElementById('quilometragem').addEventListener('focus', function (e) {
    let value = e.target.value.replace(' KM', '');
    e.target.value = value;
});

document.getElementById('quilometragem').addEventListener('blur', function (e) {
    let quilometragem = e.target.value.replace(/\D/g, '');
    if (quilometragem) {
        quilometragem = quilometragem.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        e.target.value = quilometragem + ' KM';
    }
});