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
                $(".sub-message").text(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
});

