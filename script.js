function validateRegisterForm() {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    if (!name || !email || !password) {
        alert('Please fill in all fields.');
        return false;
    }

    if (password.length < 6) {
        alert('Password must be at least 6 characters.');
        return false;
    }

    return true;
}

function validateLoginForm() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    if (!email || !password) {
        alert('Please enter both email and password.');
        return false;
    }

    return true;
}

function validateUploadForm() {
    const fileInput = document.getElementById('file');
    if (!fileInput.value) {
        alert('Please choose a file to upload.');
        return false;
    }
    return true;
}

function validateCheckForm() {
    const fileInput = document.getElementById('suspicious_file');
    if (!fileInput || !fileInput.value) {
        alert('Please choose a suspicious file to check.');
        return false;
    }
    return true;
}
