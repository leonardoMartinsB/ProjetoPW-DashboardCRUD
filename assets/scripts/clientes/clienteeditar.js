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

function formatarCPF(cpf) {
    cpf = cpf.replace(/\D/g, ''); 
    if (cpf.length > 11) {
        cpf = cpf.substring(0, 11); 
    }
    if (cpf.length <= 11) {
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    }
    return cpf;
}

function formatarCelular(celular) {
    celular = celular.replace(/\D/g, '');
    celular = celular.substring(0, 11);
    if (celular.length > 10) {
        celular = celular.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    } else if (celular.length > 0) {
        celular = celular.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
    }
    return celular;
}

const cpfInput = document.getElementById('cpf');
const telefoneInput = document.getElementById('telefone');

cpfInput.addEventListener('input', function () {
    this.value = formatarCPF(this.value);
});

telefoneInput.addEventListener('input', function () {
    this.value = formatarCelular(this.value);
});