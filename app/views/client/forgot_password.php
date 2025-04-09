<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="" id="forgotPasswordForm">
        <input type="email" name="email" id="email" placeholder="Enter your email">
        <button type="submit">Send Reset Link</button>
    </form>

    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', async function (event) {
            event.preventDefault();

            const email = document.getElementById('email').value;

            try {
                const response = await fetch('/send-reset-link', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Reset link sent to your email!');
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
</body>
</html>