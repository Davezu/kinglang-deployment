<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="" action="" id="resetPasswordForm">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">  
        <input type="password" name="new_password" placeholder="New password">
        <button type="submit">Reset Password</button>
    </form>

    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', async function (event) {
            event.preventDefault(); 

            const formData = new FormData(this);
            const token = formData.get('token');
            const newPassword = formData.get('new_password');

            try {
                const response = await fetch('/update-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ token, newPassword })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Password reset successfully!');
                    window.location.href = '/home/login'; 
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