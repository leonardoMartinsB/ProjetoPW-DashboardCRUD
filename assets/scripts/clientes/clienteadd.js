document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('clienteForm');
    const successAlert = document.getElementById('successAlert');
    const cpfInput = document.getElementById('cpf');
    const telefoneInput = document.getElementById('telefone');
    const imagemInput = document.getElementById('imagem');
    const previewContainer = document.getElementById('preview-container');

    function formatarCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');
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

    cpfInput.addEventListener('input', function () {
        this.value = formatarCPF(this.value);
    });

    telefoneInput.addEventListener('input', function () {
        this.value = formatarCelular(this.value);
    });

    imagemInput.addEventListener('change', function (event) {
        const file = event.target.files[0]; 

        previewContainer.innerHTML = '';

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const imgElement = document.createElement('img');
                imgElement.src = e.target.result; 
                imgElement.classList.add('preview-img');

                previewContainer.appendChild(imgElement);
            };

            reader.readAsDataURL(file);
        }
    });

    document.getElementById('clear-button').addEventListener('click', function () {
        previewContainer.innerHTML = '';
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(form);

        fetch('clienteadd.php', {
            method: 'POST',
            body: formData
        }).then(response => response.text()).then(result => {
            if (result.includes('Cliente registrado com sucesso')) {
                successAlert.style.display = 'block';
                form.reset();
                previewContainer.innerHTML = '';
                setTimeout(function () {
                    successAlert.style.display = 'none';
                }, 5000);
            } else {
                console.error('Erro ao registrar cliente:', result);
            }
        }).catch(error => {
            console.error('Erro na requisição:', error);
        });
    });
});