let modal = document.getElementById('cropImageModal');
let image = document.getElementById('image');
let cropper;

document.body.addEventListener("change", function(e) {
    if (e.target.classList.contains("image-input")) {
        let files = e.target.files;
        let done = function(url) {
            image.src = url;
            modal.style.display = "block"
            modal.classList.add('show');
        };
        let reader;
        let file;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function(e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    }
});

image.addEventListener('load', function() {
    cropper = new Cropper(image, {
        aspectRatio: 1
        , viewMode: 3
        , minContainerWidth: image.width < 200 && image.height < 200 ? image.width * 2 : image.width
        , minContainerHeight: image.width < 200 && image.height < 200 ? image.height * 2 : image.height
        , });
}, false);

function closeModal() {
    modal.style.display = "none"
    modal.classList.remove("show")

    cropper.destroy();
    cropper = null;
}

document.getElementById('crop').addEventListener('click', function() {
    let canvas = cropper.getCroppedCanvas({
        width: 160
        , height: 160
        , });

    canvas.toBlob(function(blob) {
        let url = URL.createObjectURL(blob);
        let preview = document.getElementById('preview');
        preview.src = url;

        modal.style.display = 'none';

        cropper.destroy();
        cropper = null;
    });
});

document.getElementById('update-form').addEventListener('submit', function(e) {
    e.preventDefault();

    let form = e.target;
    let formData = new FormData(form);

    let imageSrc = document.getElementById('preview').src;

    fetch(imageSrc)
        .then(res => res.blob())
        .then(blob => formData.append('image_url', blob, 'user_image.png'))
        .then(() => {
            fetch(form.action, {
                method: 'POST'
                , body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        console.log('deu ruim')
                        treatError(response)
                        return
                    } else if (response.redirected) {
                        console.log('deu bom com')
                        console.log(response)
                        window.location.href = response.url;
                    }
                    return response.json();
                })
        });
});

function treatError(response) {
    response.json().then(data => {
        displayErrors(data.errors)
    })
}

function displayErrors(errors) {
    clearErrors();

    let alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger';

    let errorList = document.createElement('ul');

    Object.keys(errors).forEach(field => {
        let input = document.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            let errorContainer = document.createElement('span');
            errorContainer.className = 'invalid-feedback';
            errorContainer.setAttribute('role', 'alert');
            errorContainer.style.display = 'inherit';
            errorContainer.innerHTML = `<strong>${errors[field][0]}</strong>`;
            input.parentNode.appendChild(errorContainer);

            let errorItem = document.createElement('li');
            errorItem.textContent = errors[field][0];
            errorList.appendChild(errorItem);
        }
    });

    alertDiv.appendChild(errorList);

    let form = document.querySelector('form');
    form.insertBefore(alertDiv, form.firstChild);
}

function clearErrors() {
    let errorMessages = document.querySelectorAll('.invalid-feedback');
    errorMessages.forEach(message => message.parentNode.removeChild(message));

    let inputs = document.querySelectorAll('.is-invalid');
    inputs.forEach(input => input.classList.remove('is-invalid'));

    let alertDiv = document.querySelector('.alert-danger');
    if (alertDiv) {
        alertDiv.parentNode.removeChild(alertDiv);
    }
}