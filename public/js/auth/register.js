document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.querySelector("#phone");
    window.intlTelInput(phoneInput, {
        initialCountry: "pt",
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
    });

    document.querySelectorAll('.form-step:not(#step-1)').forEach(function (step) {
        step.style.display = 'none';
    });

    let currentStep = 1;

    document.querySelectorAll('.next-step').forEach(function (button) {
        button.addEventListener('click', function () {
            if (validateStep(currentStep)) {
                document.getElementById('step-' + currentStep).style.display = 'none';
                currentStep++;
                document.getElementById('step-' + currentStep).style.display = 'block';
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('step-' + currentStep).style.display = 'none';
            currentStep--;
            document.getElementById('step-' + currentStep).style.display = 'block';
        });
    });

    function validateStep(step) {
        let valid = true;
        if (step === 1) {
            valid = validateStep1();
        } else if (step === 2) {
            valid = validateStep2();
        }
        return valid;
    }

    function validateStep1() {
        let valid = true;
        let name = document.getElementById('name').value;
        let universityId = document.getElementById('university_id').value;
        let phone = document.getElementById('phone').value;
        let birthdate = document.getElementById('birthdate').value;

        if (!name || !universityId || !phone || !birthdate) {
            alert('Please fill in all fields in Step 1');
            valid = false;
        } else {
            let currentDate = new Date();
            let inputDate = new Date(birthdate);

            if (inputDate > currentDate) {
                displayError('Birthdate cannot be greater than the current date', 'birthdate');
                valid = false;
            } else {
                clearError('birthdate');
            }
        }

        return valid;
    }


    function validateStep2() {
        let valid = true;

        let email = document.getElementById('email').value;
        let password = document.getElementById('password').value;
        let confirmPassword = document.getElementById('password-confirm').value;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email || !emailRegex.test(email)) {
            displayError('Invalid email address', 'email');
            valid = false;
        } else {
            clearError('email');
        }

        if (!password || password.length < 8) {
            displayError('Password must be at least 8 characters', 'password');
            valid = false;
        } else {
            clearError('password');
        }

        if (password !== confirmPassword) {
            displayError('Passwords do not match', 'password-confirm');
            valid = false;
        } else {
            clearError('password-confirm');
        }

        return valid;
    }

    function displayError(message, input) {
        let errorMessageElement = document.getElementById('error-message-' + input);
        errorMessageElement.innerHTML = `<strong>${message}</strong>`;
        errorMessageElement.style.display = 'inherit';
        document.getElementById(input).classList.add('is-invalid');
    }

    function clearError(input) {
        let errorMessageElement = document.getElementById('error-message-' + input);
        errorMessageElement.innerHTML = '';
        errorMessageElement.style.display = 'none';
        document.getElementById(input).classList.remove('is-invalid');
    }

});

let modal = document.getElementById('cropImageModal');
let image = document.getElementById('image');
let cropper;

document.body.addEventListener("change", function (e) {
    if (e.target.classList.contains("image-input")) {
        let files = e.target.files;
        let done = function (url) {
            image.src = url;
            // document.getElementById("backdrop").style.display = "block"
            modal.style.display = "block"
            modal.classList.add('show');
            console.log('deve abrir modal')
        };
        let reader;
        let file;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    }
});

image.addEventListener('load', function () {
    cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 3,
        minContainerWidth: image.width < 200 && image.height < 200 ? image.width * 2 : image.width,
        minContainerHeight: image.width < 200 && image.height < 200 ? image.height * 2 : image.height,
    });
}, false);

function closeModal() {
    // document.getElementById("backdrop").style.display = "none"
    modal.style.display = "none"
    modal.classList.remove("show")

    cropper.destroy();
    cropper = null;
}

document.getElementById('crop').addEventListener('click', function () {
    let canvas = cropper.getCroppedCanvas({
        width: 160,
        height: 160,
    });

    canvas.toBlob(function (blob) {
        let url = URL.createObjectURL(blob);
        let preview = document.getElementById('preview');
        preview.src = url;

        modal.style.display = 'none';

        cropper.destroy();
        cropper = null;
    });
});

document.getElementById('register-form').addEventListener('submit', function (e) {
    e.preventDefault();

    let form = e.target;
    let formData = new FormData(form);

    let imageSrc = document.getElementById('preview').src;

    fetch(imageSrc)
        .then(res => res.blob())
        .then(blob => formData.append('image_url', blob, 'user_image.png'))
        .then(() => {
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        treatError(response)
                        return
                    } else if (response.redirected) {
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

function validatePhoneInput(inputField) {
    inputField.value = inputField.value.replace(/[^0-9+ ]/g, '');
    const errorMessageBox = document.getElementById('error-message-phone');

    if (/[^0-9+ ]/.test(inputField.value)) {
        errorMessageBox.innerHTML = '<strong>Invalid input. Please enter only numbers, +, or space.</strong>';
        errorMessageBox.style.display = 'inherit';
    } else {
        errorMessageBox.innerHTML = '';
        errorMessageBox.style.display = 'none';
    }
}


