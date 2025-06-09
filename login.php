<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$admin_username = $_ENV['ADMIN_USERNAME'];
$admin_password = $_ENV['ADMIN_PASSWORD'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email === $admin_username && $password === $admin_password) {
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $email;
        header('Location: index.php');
        exit;
    } else {
        $error = 'E-mail ou senha incorretos';
    }
}

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar na sua conta - Larvo</title>
    <link rel="icon" href="https://via.placeholder.com/32x32" />
    <style>
        :root {
            --background: #f9f5ff;
            --content-background: #ffffff;
            --featured: #b399d4;
            --featured-foreground: #9f7fdb;
            --primary: #b399d4;
            --primary-foreground: #ffffff;
            --input-border: #d1c4e9;
            --ring: #9575cd;
            --muted-foreground: #7e57c2;
            --background-alt: #ede7f6;
            --error: #ff5252;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
        }
        
        body {
            background-color: var(--background);
            color: #333;
            min-height: 100vh;
            display: flex;
        }
        
        .container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        .logo-section {
            flex: 0 0 40%; /* Aumentado para 40% da largura */
            background-color: var(--featured);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }
        
        /* Divisor arredondado */
        .logo-section::after {
            content: '';
            position: absolute;
            right: -25px;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 100px;
            background-color: var(--featured);
            border-radius: 50%;
            z-index: 2;
        }
        
        .logo-container {
            max-width: 300px; /* Aumentado o tamanho máximo */
            text-align: center;
        }
        
        .logo {
            max-width: 220px; /* Logo maior - aumentado de 120px */
            margin-bottom: 1.5rem;
            object-fit: contain;
        }
        
        .slogan {
            color: white;
            font-size: 1.2rem; /* Texto um pouco maior */
            font-weight: 300;
            margin-top: 0.5rem;
        }
        
        .login-section {
            flex: 1; /* Ocupa o restante do espaço */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background-color: var(--content-background);
        }
        
        .login-container {
            max-width: 500px;
            width: 100%;
            padding: 0 2rem;
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 2.5rem;
            color: var(--muted-foreground);
        }
        
        .form-group {
            margin-bottom: 2rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.8rem;
            font-weight: 500;
            color: var(--muted-foreground);
            font-size: 1.1rem;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        input:focus {
            outline: none;
            border-color: var(--ring);
            box-shadow: 0 0 0 3px rgba(149, 117, 205, 0.2);
        }
        
        .forgot-password {
            display: block;
            text-align: right;
            font-size: 1rem;
            margin-top: 0.8rem;
            color: var(--muted-foreground);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background-color: var(--featured);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        
        .submit-btn:hover {
            background-color: var(--featured-foreground);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .register-link {
            text-align: center;
            margin-top: 2rem;
            font-size: 1rem;
            color: var(--muted-foreground);
        }
        
        .error-message {
            color: var(--error);
            text-align: center;
            margin: 1.5rem 0;
            font-size: 1rem;
            padding: 0.8rem;
            background-color: rgba(255, 82, 82, 0.1);
            border-radius: 6px;
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .logo-section {
                flex: 0 0 180px; /* Altura maior em mobile */
                width: 100%;
                padding: 1.5rem;
            }
            
            .logo-section::after {
                display: none;
            }
            
            .logo {
                max-width: 160px; /* Logo maior em mobile */
            }
            
            .slogan {
                font-size: 1rem;
            }
            
            .login-section {
                padding: 2rem 1.5rem;
            }
            
            .login-container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Seção da Logo (aumentada) -->
        <div class="logo-section">
            <div class="logo-container">
                <img src="img/logolarvo.png" alt="Logo Larvo" class="logo">
            </div>
        </div>
        
        <!-- Seção de Login -->
        <div class="login-section">
            <div class="login-container">
                <h1 class="login-title">Acesse sua conta</h1>
                
                <?php if (isset($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" required>
                        <a href="#" class="forgot-password">Esqueceu sua senha?</a>
                    </div>
                    
                    <button type="submit" class="submit-btn">Entrar</button>
                </form>
                
                <div class="register-link">
                    Novo por aqui? <a href="#">Crie sua conta</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>