<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - restoCampus</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #a8b5c8 0%, #c9d4df 50%, #e8ecf1 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .food-icons {
            position: absolute;
            font-size: 40px;
            opacity: 0.15;
            animation: float 6s ease-in-out infinite;
        }

        .food-icons:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .food-icons:nth-child(2) { top: 20%; right: 15%; animation-delay: 1s; }
        .food-icons:nth-child(3) { bottom: 15%; left: 15%; animation-delay: 2s; }
        .food-icons:nth-child(4) { bottom: 20%; right: 10%; animation-delay: 1.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .login-container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
            animation: slideIn 0.6s ease-out;
            position: relative;
            z-index: 1;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            padding: 45px 30px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '🍴';
            position: absolute;
            top: -20px;
            left: -20px;
            font-size: 100px;
            opacity: 0.1;
            transform: rotate(-15deg);
        }

        .header::after {
            content: '🥘';
            position: absolute;
            bottom: -30px;
            right: -20px;
            font-size: 100px;
            opacity: 0.1;
            transform: rotate(15deg);
        }

        .logo {
            font-size: 56px;
            margin-bottom: 12px;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 8px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header p {
            font-size: 15px;
            opacity: 0.95;
            font-weight: 500;
        }

        .form-container {
            padding: 45px 35px;
            background: linear-gradient(to bottom, #ffffff 0%, #fef9f5 100%);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #ff6b35;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
        }

        input {
            width: 100%;
            padding: 16px 18px 16px 52px;
            border: 2px solid #ffe4d6;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
            background: white;
        }

        input:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
            background: #fffbf8;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
        }

        .remember-me input[type="checkbox"] {
            width: auto;
            padding: 0;
            cursor: pointer;
            accent-color: #ff6b35;
        }

        .forgot-password {
            color: #ff6b35;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #f7931e;
        }

        .login-btn {
            width: 100%;
            padding: 17px;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.3);
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.4);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .divider {
            text-align: center;
            margin: 28px 0;
            position: relative;
            color: #999;
            font-size: 14px;
            font-weight: 500;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 2px;
            background: linear-gradient(to right, transparent, #ffe4d6, transparent);
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .signup-link {
            text-align: center;
            margin-top: 22px;
            color: #666;
            font-size: 15px;
        }

        .signup-link a {
            color: #ff6b35;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: #f7931e;
        }

        .error-message {
            background: #ffe4e4;
            color: #d63031;
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 22px;
            font-size: 14px;
            display: none;
            border-left: 4px solid #d63031;
        }

        .chef-badge {
            display: inline-block;
            background: #ff6b35;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="food-icons">🍕</div>
    <div class="food-icons">🍔</div>
    <div class="food-icons">🥗</div>
    <div class="food-icons">🍜</div>

    <div class="login-container">
        <div class="header">
            <div class="logo">👨‍🍳</div>
            <h1>restoCampus</h1>
            <p>Votre restaurant universitaire</p>
            <span class="chef-badge">🔥 Menus du jour</span>
        </div>
        
        <div class="form-container">
            <div class="error-message" id="errorMessage">
                ❌ Identifiants incorrects. Veuillez réessayer.
            </div>
            
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">📧 Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✉️</span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="votre.email@campus.fr"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">🔐 Mot de passe</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔑</span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <span>Se souvenir de moi</span>
                    </label>
                    <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="login-btn">🍽️ Se connecter</button>

                <div class="divider">ou</div>

                <div class="signup-link">
                    Nouveau sur restoCampus ? <a href="#">Créer un compte 👋</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const errorMessage = document.getElementById('errorMessage');

        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;

            console.log('Tentative de connexion:', { email, remember });
            
            if (email && password) {
                alert('🎉 Connexion réussie !\n🍴 Bienvenue sur restoCampus\n\nBon appétit ! 👨‍🍳');
            } else {
                errorMessage.style.display = 'block';
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 3000);
            }
        });

        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>