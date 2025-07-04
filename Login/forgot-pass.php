<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion 24 - Login</title>
    <link rel="icon" href="../Img/logoBusanaSatu.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../Style/login.css">
    <style>
        /* Enhanced Loading Animation Styles */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, 
                rgba(0, 0, 0, 0.8), 
                rgba(52, 152, 219, 0.3), 
                rgba(155, 89, 182, 0.3)
            );
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
            backdrop-filter: blur(10px);
        }
        
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .loading-spinner {
            position: relative;
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }
        
        .spinner-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 4px solid transparent;
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
        }
        
        .spinner-ring:nth-child(1) {
            border-top: 4px solid #3498db;
            animation-delay: 0s;
        }
        
        .spinner-ring:nth-child(2) {
            border-right: 4px solid #e74c3c;
            animation-delay: -0.3s;
            animation-duration: 1.2s;
        }
        
        .spinner-ring:nth-child(3) {
            border-bottom: 4px solid #f39c12;
            animation-delay: -0.6s;
            animation-duration: 1.8s;
        }
        
        .spinner-ring:nth-child(4) {
            border-left: 4px solid #2ecc71;
            animation-delay: -0.9s;
            animation-duration: 2.1s;
        }
        
        .spinner-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            background: linear-gradient(45deg, #3498db, #e74c3c);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes pulse {
            0%, 100% { 
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
            50% { 
                transform: translate(-50%, -50%) scale(1.5);
                opacity: 0.7;
            }
        }
        
        .loading-text {
            color: #fff;
            font-size: 1.2em;
            font-weight: 600;
            margin-bottom: 10px;
            animation: fadeInOut 2s ease-in-out infinite;
        }
        
        .loading-dots {
            color: #fff;
            font-size: 1.5em;
            letter-spacing: 2px;
        }
        
        .loading-dots span {
            animation: blink 1.4s infinite;
        }
        
        .loading-dots span:nth-child(1) { animation-delay: 0s; }
        .loading-dots span:nth-child(2) { animation-delay: 0.2s; }
        .loading-dots span:nth-child(3) { animation-delay: 0.4s; }
        
        @keyframes fadeInOut {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            25%, 75% { opacity: 0.3; }
        }
        
        .btn-loading {
            position: relative;
            overflow: hidden;
        }
        
        .btn-loading .spinner-border {
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -12px;
            margin-top: -12px;
            display: none;
        }
        
        .btn-loading.loading {
            color: transparent;
        }
        
        .btn-loading.loading::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        /* Additional floating particles effect */
        .loading-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #fff;
            border-radius: 50%;
            animation: float 3s infinite ease-in-out;
        }
        
        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 0.5s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 1s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 1.5s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 2s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 2.5s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 3s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 3.5s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 4s; }
        
        @keyframes float {
            0%, 100% { 
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10%, 90% { 
                opacity: 1;
                transform: scale(1);
            }
            50% { 
                transform: translateY(-10vh) scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="form-section">
            <h2 class="form-title">Kamu Lupa Password ?</h2>
            <p class="text-muted">Silahkan Masukkan <span class="fw-bold">Email</span> Untuk Reset Password!<br>Email Harus Valid Sesuai dengan <span class="fw-bold">Username</span> Kamu!</p>
            <form method="POST" action="send-pass-reset.php" id="resetForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your Email" required>
                </div>
                <button type="submit" name="submit" class="btn btn-black w-100 mb-3 btn-loading" id="submitBtn">
                    <span class="btn-text">Send</span>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
                <div class="text-center">
                    <a href="formLogin.php" class="text-center text-decoration-none">Kembali Ke Login</a>
                </div>
            </form>
        </div>
        <div class="image-section">
            <div class="overlay">
                <img src="../Img/model.jpg" alt="model" class="img-fluid">
            </div>
        </div>
    </div>

    <!-- Enhanced Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        <div class="loading-container">
            <div class="loading-spinner">
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-center"></div>
            </div>
            <div class="loading-text">Mengirim Email Reset Password</div>
            <div class="loading-dots">
                <span>.</span>
                <span>.</span>
                <span>.</span>
            </div>
        </div>
    </div>

    <script>
        // Form submission with enhanced loading animation
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Show loading state on button
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            submitBtn.querySelector('.btn-text').textContent = 'Sending...';
            submitBtn.querySelector('.spinner-border').style.display = 'inline-block';
            
            // Show full-page loading overlay with enhanced animations
            loadingOverlay.style.display = 'flex';
            
            // Add subtle fade-in effect
            setTimeout(() => {
                loadingOverlay.style.opacity = '1';
            }, 10);
            
            // If you want to hide the overlay when the form submission is complete,
            // you'll need to handle that in the response from send-pass-reset.php
            // For now, it will stay visible until page reload/redirect
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>