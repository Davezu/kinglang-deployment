<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Kinglang Booking</title>
    <link href="/public/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="/public/icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 0 20px;
        }
        
        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
            animation: bounce 1s ease-in-out;
        }
        
        @keyframes bounce {
            0%, 20%, 60%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            80% {
                transform: translateY(-5px);
            }
        }
        
        .success-title {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .success-message {
            color: #6c757d;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .payment-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 18px;
            color: #28a745;
        }
        
        .detail-label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .detail-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-outline-secondary {
            border: 2px solid #6c757d;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }
        
        .booking-info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .booking-info h5 {
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .success-card {
                padding: 30px 20px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-primary,
            .btn-outline-secondary {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            
            <h1 class="success-title">Payment Successful!</h1>
            <p class="success-message">
                Your payment has been processed successfully. Thank you for choosing Kinglang Booking!
            </p>
            
            <?php if (isset($paymentData['booking'])): ?>
            <div class="booking-info">
                <h5><i class="bi bi-geo-alt-fill"></i> Booking Details</h5>
                <p><strong>Destination:</strong> <?php echo htmlspecialchars($paymentData['booking']['destination']); ?></p>
                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($paymentData['booking']['date_of_tour'])); ?></p>
                <p><strong>Booking ID:</strong> #<?php echo $paymentData['booking_id']; ?></p>
            </div>
            <?php endif; ?>
            
            <div class="payment-details">
                <div class="detail-row">
                    <span class="detail-label">Payment ID:</span>
                    <span class="detail-value">#<?php echo $paymentData['payment_id'] ?? 'N/A'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">PayMongo (GCash)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date & Time:</span>
                    <span class="detail-value"><?php echo date('F j, Y g:i A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount Paid:</span>
                    <span class="detail-value">₱<?php echo number_format($paymentData['amount'] ?? 0, 2); ?></span>
                </div>
            </div>
            
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle-fill"></i>
                <strong>What's Next?</strong>
                <br>Your payment is being processed and will be confirmed by our admin team. You'll receive a notification once confirmed.
            </div>
            
            <div class="action-buttons">
                <a href="/home/booking-requests" class="btn btn-primary">
                    <i class="bi bi-list-ul"></i> View My Bookings
                </a>
                <a href="/home" class="btn btn-outline-secondary">
                    <i class="bi bi-house-fill"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
    
    <script src="/public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-redirect after 30 seconds
        setTimeout(function() {
            if (confirm('Would you like to view your booking details?')) {
                window.location.href = '/home/booking-requests';
            }
        }, 30000);
        
        // Confetti effect (optional)
        function createConfetti() {
            const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#f0932b', '#eb4d4b'];
            
            for (let i = 0; i < 100; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.width = '10px';
                    confetti.style.height = '10px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.left = Math.random() * window.innerWidth + 'px';
                    confetti.style.top = '-10px';
                    confetti.style.zIndex = '1000';
                    confetti.style.borderRadius = '50%';
                    confetti.style.pointerEvents = 'none';
                    
                    document.body.appendChild(confetti);
                    
                    const animation = confetti.animate([
                        { transform: 'translateY(0px) rotate(0deg)', opacity: 1 },
                        { transform: `translateY(${window.innerHeight + 10}px) rotate(720deg)`, opacity: 0 }
                    ], {
                        duration: 3000,
                        easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
                    });
                    
                    animation.onfinish = () => confetti.remove();
                }, i * 50);
            }
        }
        
        // Trigger confetti on load
        window.addEventListener('load', createConfetti);
    </script>
</body>
</html>
