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
                $(".message-text").text(response.message);
                $("#signupForm")[0].reset();
            } else {
                $(".message-text").text(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
});