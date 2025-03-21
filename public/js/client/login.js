$('#loginForm').submit(function (e) {
    e.preventDefault();

    var formData = {
        email: $("#email").val(),
        password: $("#password").val()
    }

    $.ajax({
        url: "/client/login",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(formData),
        success: function (response) {  
            if (response.success) {
                window.location.href = response.redirect;
            } else {
                $(".sub-message").text(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
});

