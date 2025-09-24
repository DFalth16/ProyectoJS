<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    header('Location: panel.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login - Vet Clinic</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary-color: #0b74da;
      --secondary-color: #34c38f;
      --accent-color: #ff914d;
      --light-color: #e8f4f8;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      background: linear-gradient(135deg, #e0f7fa 0%, #b3e5fc 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      position: relative;
      overflow: hidden;
    }
    
    #background-animation {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      overflow: hidden;
    }
    
    .bubble {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.4);
      animation: float 15s infinite ease-in-out;
      bottom: -100px;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }
    
    .paw-print {
      position: absolute;
      opacity: 0.15;
      animation: float 25s infinite linear;
      filter: drop-shadow(0 0 3px rgba(0, 0, 0, 0.1));
    }
    
    .bone {
      position: absolute;
      opacity: 0.1;
      animation: float 30s infinite linear;
      transform: rotate(45deg);
    }
    
    @keyframes float {
      0% {
        transform: translateY(0) rotate(0deg);
        opacity: 0;
      }
      10% {
        opacity: 0.6;
      }
      90% {
        opacity: 0.3;
      }
      100% {
        transform: translateY(-100vh) rotate(360deg);
        opacity: 0;
      }
    }
    
    @keyframes pulse {
      0% {
        transform: scale(1);
        box-shadow: 0 5px 15px rgba(11, 116, 218, 0.3);
      }
      50% {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(11, 116, 218, 0.5);
      }
      100% {
        transform: scale(1);
        box-shadow: 0 5px 15px rgba(11, 116, 218, 0.3);
      }
    }
    
    .login-container {
      max-width: 450px;
      width: 100%;
      margin: 20px;
      z-index: 10;
    }

    .card-login {
      border-radius: 20px;
      background: rgba(255, 255, 255, 0.95);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      backdrop-filter: blur(10px);
      border: none;
      overflow: hidden;
      position: relative;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .card-login:hover {
      transform: translateY(-5px);
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    }
    
    .card-login::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
    }

    .card-header {
      background: transparent;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      padding: 2.5rem 2rem 1.5rem;
      text-align: center;
      position: relative;
    }

    .logo-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 1rem;
    }

    .logo-icon {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      box-shadow: 0 5px 15px rgba(11, 116, 218, 0.3);
      animation: pulse 3s infinite;
      position: relative;
      z-index: 1;
    }
    
    .logo-icon::after {
      content: "";
      position: absolute;
      top: -5px;
      left: -5px;
      right: -5px;
      bottom: -5px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      z-index: -1;
      opacity: 0.3;
      filter: blur(10px);
    }

    .logo-icon i {
      font-size: 2.8rem;
      color: white;
    }

    .card-header h2 {
      margin: 0;
      font-weight: 800;
      color: var(--primary-color);
      font-size: 2.2rem;
      letter-spacing: -0.5px;
    }

    .card-header p {
      color: #6c757d;
      margin-bottom: 0;
      font-size: 1.1rem;
    }

    .card-body {
      padding: 2rem 2.5rem 2.5rem;
    }

    .form-control {
      border-radius: 12px;
      padding: 14px 18px;
      border: 1px solid #e1e5eb;
      transition: all 0.3s;
      font-size: 1rem;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(11, 116, 218, 0.15);
    }

    .input-group-text {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      border: 1px solid #e1e5eb;
      border-right: none;
      border-radius: 12px 0 0 12px;
      padding: 0 18px;
    }

    .input-group .form-control {
      border-left: none;
      border-radius: 0 12px 12px 0;
    }

    .form-label {
      font-weight: 600;
      color: #495057;
      margin-bottom: 8px;
      font-size: 0.95rem;
    }

    .btn-login {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      border-radius: 12px;
      padding: 14px;
      font-weight: 700;
      font-size: 1.1rem;
      color: white;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(11, 116, 218, 0.3);
      position: relative;
      overflow: hidden;
    }
    
    .btn-login::before {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s;
    }

    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(11, 116, 218, 0.4);
    }
    
    .btn-login:hover::before {
      left: 100%;
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .btn-login:disabled {
      opacity: 0.7;
      transform: none;
    }

    .alert-custom {
      border-radius: 12px;
      border: none;
      padding: 14px 18px;
      font-weight: 500;
    }

    .alert-danger {
      background-color: rgba(220, 53, 69, 0.1);
      color: #dc3545;
      border-left: 4px solid #dc3545;
    }

    .alert-success {
      background-color: rgba(40, 167, 69, 0.1);
      color: #28a745;
      border-left: 4px solid #28a745;
    }

    .footer-text {
      text-align: center;
      color: #6c757d;
      font-size: 0.9rem;
      margin-top: 2rem;
      font-weight: 500;
    }

    .animal-icons {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 1.5rem;
      opacity: 0.8;
    }

    .animal-icons i {
      font-size: 1.4rem;
      color: var(--primary-color);
      transition: all 0.3s;
    }
    
    .animal-icons i:hover {
      color: var(--accent-color);
      transform: translateY(-3px);
    }

    .floating-pet {
      position: absolute;
      font-size: 3rem;
      opacity: 0.1;
      z-index: -1;
      animation: float-pet 20s infinite linear;
    }
    
    @keyframes float-pet {
      0% {
        transform: translateX(-100px) rotate(0deg);
      }
      50% {
        transform: translateX(calc(100vw + 100px)) rotate(180deg);
      }
      100% {
        transform: translateX(-100px) rotate(360deg);
      }
    }

    @media (max-width: 576px) {
      .login-container {
        margin: 15px;
      }
      
      .card-header {
        padding: 2rem 1.5rem 1rem;
      }
      
      .card-body {
        padding: 1.5rem 1.5rem 2rem;
      }
      
      .logo-icon {
        width: 70px;
        height: 70px;
      }
      
      .logo-icon i {
        font-size: 2.3rem;
      }
      
      .card-header h2 {
        font-size: 1.8rem;
      }
    }
  </style>
</head>
<body>

  <!-- Fondo animado con burbujas, huellas y huesos -->
  <div id="background-animation"></div>
  
  <!-- Mascotas flotantes -->
  <i class="floating-pet bi bi-bug" style="top: 10%; animation-duration: 25s;"></i>
  <i class="floating-pet bi bi-egg-fried" style="top: 30%; animation-duration: 30s; animation-delay: 2s;"></i>
  <i class="floating-pet bi bi-heart-fill" style="top: 70%; animation-duration: 35s; animation-delay: 5s;"></i>

  <div class="login-container">
    <div class="card card-login">
      <div class="card-header">
        <div class="logo-icon">
          <i class="bi bi-heart-pulse"></i>
        </div>
        <h2>Vet Clinic</h2>
        <p class="mb-0">Cuidamos de tus mejores amigos</p>
      </div>
      
      <div class="card-body">
        <div id="app">
          <div v-if="error" class="alert alert-danger alert-custom d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <span>{{ error }}</span>
          </div>
          <div v-if="success" class="alert alert-success alert-custom d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            <span>{{ success }}</span>
          </div>

          <form @submit.prevent="submit">
            <div class="mb-3">
              <label for="user" class="form-label">Usuario</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input id="user" v-model="form.user" type="text" class="form-control" placeholder="Ingresa tu usuario" required maxlength="50" autocomplete="username" />
              </div>
            </div>

            <div class="mb-4">
              <label for="pass" class="form-label">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input id="pass" v-model="form.pass" type="password" class="form-control" placeholder="Ingresa tu contraseña" required maxlength="100" autocomplete="current-password" />
              </div>
            </div>

            <button type="submit" class="btn btn-login w-100 mb-3" :disabled="loading">
              <span v-if="!loading">
                <i class="bi bi-box-arrow-in-right me-2"></i>Entrar al Sistema
              </span>
              <span v-else>
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Verificando...
              </span>
            </button>
          </form>

          <div class="animal-icons">
            <i class="bi bi-bug" title="Animales exóticos"></i>
            <i class="bi bi-egg-fried" title="Mascotas pequeñas"></i>
            <i class="bi bi-heart-fill" title="Cuidado con amor"></i>
            <i class="bi bi-droplet" title="Vacunas"></i>
            <i class="bi bi-flower1" title="Bienestar"></i>
          </div>
        </div>
      </div>
    </div>
    
    <p class="footer-text mt-3">
      &copy; 2023 Vet Clinic. Todos los derechos reservados.
    </p>
  </div>

  <!-- Vue 3 CDN -->
  <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
  <!-- Axios CDN -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  
  <!-- Script para el fondo animado -->
  <script>
    // Crear elementos de fondo animado
    document.addEventListener('DOMContentLoaded', function() {
      const background = document.getElementById('background-animation');
      
      // Crear burbujas
      for (let i = 0; i < 20; i++) {
        createBubble();
      }
      
      // Crear huellas
      for (let i = 0; i < 15; i++) {
        createPawPrint();
      }
      
      // Crear huesos
      for (let i = 0; i < 10; i++) {
        createBone();
      }
      
      function createBubble() {
        const bubble = document.createElement('div');
        bubble.classList.add('bubble');
        
        // Tamaño aleatorio
        const size = Math.random() * 80 + 20;
        bubble.style.width = `${size}px`;
        bubble.style.height = `${size}px`;
        
        // Posición inicial aleatoria
        bubble.style.left = `${Math.random() * 100}%`;
        
        // Duración de animación aleatoria
        const duration = Math.random() * 20 + 10;
        bubble.style.animationDuration = `${duration}s`;
        
        // Retraso aleatorio
        bubble.style.animationDelay = `${Math.random() * 5}s`;
        
        background.appendChild(bubble);
        
        // Reiniciar la animación cuando termine
        bubble.addEventListener('animationend', function() {
          bubble.style.left = `${Math.random() * 100}%`;
          bubble.style.animationDelay = '0s';
        });
      }
      
      function createPawPrint() {
        const pawPrint = document.createElement('div');
        pawPrint.classList.add('paw-print');
        
        // SVG de huella de perro/gato
        pawPrint.innerHTML = `
          <svg width="50" height="50" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12,2A10,10,0,0,0,4.65,18.76l-1.72,1.71a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0l1.71-1.72A10,10,0,1,0,12,2Zm0,18a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"/>
            <circle cx="8.5" cy="10.5" r="1.5"/>
            <circle cx="15.5" cy="10.5" r="1.5"/>
            <path d="M12,16a4,4,0,0,1-3.17-1.5,1,1,0,0,0-1.58,1.22A6,6,0,0,0,12,18a6,6,0,0,0,4.75-2.28,1,1,0,0,0-1.58-1.22A4,4,0,0,1,12,16Z"/>
          </svg>
        `;
        
        // Tamaño aleatorio
        const size = Math.random() * 40 + 20;
        pawPrint.style.width = `${size}px`;
        pawPrint.style.height = `${size}px`;
        
        // Posición inicial aleatoria
        pawPrint.style.left = `${Math.random() * 100}%`;
        
        // Duración de animación aleatoria
        const duration = Math.random() * 30 + 20;
        pawPrint.style.animationDuration = `${duration}s`;
        
        // Retraso aleatorio
        pawPrint.style.animationDelay = `${Math.random() * 15}s`;
        
        // Color aleatorio (tonos pastel)
        const colors = ['#0b74da', '#34c38f', '#ff914d', '#6f42c1', '#e83e8c'];
        pawPrint.style.color = colors[Math.floor(Math.random() * colors.length)];
        
        background.appendChild(pawPrint);
        
        // Reiniciar la animación cuando termine
        pawPrint.addEventListener('animationend', function() {
          pawPrint.style.left = `${Math.random() * 100}%`;
          pawPrint.style.animationDelay = '0s';
        });
      }
      
      function createBone() {
        const bone = document.createElement('div');
        bone.classList.add('bone');
        
        // SVG de hueso
        bone.innerHTML = `
          <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
            <path d="M20,14.18V12.73A2.73,2.73,0,0,0,17.27,10H15.82A3,3,0,0,1,14,8.18V6.73A2.73,2.73,0,0,0,11.27,4H10A2,2,0,0,0,8,6H6.73A2.73,2.73,0,0,0,4,8.73V10a2,2,0,0,0,2,2H8v3.27A2.73,2.73,0,0,0,10.73,18H12a2,2,0,0,0,2-2v-2h3.27A2.73,2.73,0,0,0,20,11.27V10a2,2,0,0,0-2-2H16V5.27A2.73,2.73,0,0,0,13.27,2.5H12A2,2,0,0,0,10,4.5H8.73A2.73,2.73,0,0,0,6,7.23V8.5A2,2,0,0,0,4,10.5v3.27A2.73,2.73,0,0,0,6.73,16.5H8a2,2,0,0,0,2,2h1.27A2.73,2.73,0,0,0,14,15.27V14.5h2a2,2,0,0,0,2-2Z"/>
          </svg>
        `;
        
        // Tamaño aleatorio
        const size = Math.random() * 35 + 15;
        bone.style.width = `${size}px`;
        bone.style.height = `${size}px`;
        
        // Posición inicial aleatoria
        bone.style.left = `${Math.random() * 100}%`;
        
        // Duración de animación aleatoria
        const duration = Math.random() * 40 + 25;
        bone.style.animationDuration = `${duration}s`;
        
        // Retraso aleatorio
        bone.style.animationDelay = `${Math.random() * 20}s`;
        
        // Color aleatorio
        const colors = ['#0b74da', '#34c38f', '#ff914d'];
        bone.style.color = colors[Math.floor(Math.random() * colors.length)];
        
        background.appendChild(bone);
        
        // Reiniciar la animación cuando termine
        bone.addEventListener('animationend', function() {
          bone.style.left = `${Math.random() * 100}%`;
          bone.style.animationDelay = '0s';
        });
      }
    });
  </script>
  
  <!-- JS externo -->
  <script src="../js/login.js"></script>

</body>
</html>