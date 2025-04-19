$('#loginForm').submit(function (e) {
    e.preventDefault();

    var formData = {
        email: $("#email").val(),
        password: $("#password").val()
    }

    $.ajax({
        url: "/admin/submit-login",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(formData),
        success: function (response) {  
            console.log(response.success);
            if (response.success) {
                console.log(response.redirect);
                window.location.href = response.redirect;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: response.message,
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred. Please try again.',
                timer: 2000,
                timerProgressBar: true
            });
        }
    });
});

