<?php
include 'database_configure.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | JobPortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/main-styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, rgba(246, 248, 253, 0.9) 0%, rgba(241, 246, 249, 0.9) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .bg-orb {
            position: absolute;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(42, 157, 244, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
            top: -200px;
            right: -200px;
        }

        .bg-orb-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(231, 76, 60, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
            bottom: -100px;
            left: -150px;
        }

        .error-container {
            text-align: center;
            max-width: 600px;
            padding: 4rem;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            box-shadow: 0 40px 80px rgba(0,0,0,0.08);
            border: 1px solid rgba(255, 255, 255, 0.5);
            animation: slideUp 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .error-code {
            font-size: 8rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-color), #2980b9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 0.5rem;
            text-shadow: 0 10px 20px rgba(42, 157, 244, 0.2);
        }

        .error-icon {
            font-size: 4rem;
            color: #e74c3c;
            margin-bottom: 1rem;
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        .btn-home {
            background: var(--primary-color);
            color: white;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            box-shadow: 0 10px 20px rgba(42, 157, 244, 0.3);
            margin-top: 2rem;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(42, 157, 244, 0.4);
            color: white;
        }

    </style>
    <link rel="icon" type="image/png" href="img/favicon.png">
</head>
<body>
    <div class="bg-orb"></div>
    <div class="bg-orb-2"></div>
    
    <div class="error-container">
        <i class="fas fa-compass error-icon"></i>
        <h1 class="error-code">404</h1>
        <h2 style="font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">Oops! Lost in the cloud.</h2>
        <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.6;">
            The page you're looking for doesn't exist or has been moved. Don't worry, even the best explorers take a wrong turn sometimes.
        </p>
        <a href="<?php echo BASE_URL; ?>/" class="btn-home">
            <i class="fas fa-home"></i> Back to Homepage
        </a>
    </div>
</body>
</html>
