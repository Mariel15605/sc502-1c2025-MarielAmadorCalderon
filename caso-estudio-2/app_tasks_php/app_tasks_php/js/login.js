document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const loginError = document.getElementById('login-error');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        fetch('api/auth.php', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'dashboard.php';
            } else {
                loginError.textContent = data.message || 'Correo o contraseña incorrectos';
                loginError.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error al autenticar:', error);
            loginError.textContent = 'Hubo un error al conectar con el servidor';
            loginError.style.display = 'block';
        });
    });
});
