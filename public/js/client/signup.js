$("#signupForm").submit(function (e) {
    e.preventDefault();

    var formData = {
        firstName: $("#firstName").val(),
        lastName: $("#lastName").val(),
        email: $("#email").val(),
        contactNumber: $("#contactNumber").val(),
        password: $("#password").val(),
        confirmPassword: $("#confirmPassword").val()
    }

    console.log(formData);

    $.ajax({
        url: "/client/signup",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(formData),
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    // Redirect to login page after success message
                    window.location.href = "/home/login";
                });
                $("#signupForm")[0].reset();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
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