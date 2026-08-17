<template>
  <div class="login-container">
    <div class="login-wrapper">
      <!-- Panel izquierdo - Imagen/Decoración -->
      <div class="login-left">
        <div class="brand-content">
          <div class="imagenLogo">
            <img src="/images/Logo_blanco.png" alt="Logo Concejo Municipal" />
          </div>
          <h1>Bienvenido</h1>
          <p>Inicie sesión para acceder a su cuenta institucional y gestionar los módulos del Sistema Integral del Concejo Municipal de Potosí.</p>
          <div class="brand-features">
            <div class="feature">
              <span class="feature-icon">✓</span>
              <span>Gestor de Comunicación</span>
            </div>
            <div class="feature">
              <span class="feature-icon">✓</span>
              <span>Gestor Documental</span>
            </div>
            <div class="feature">
              <span class="feature-icon">✓</span>
              <span>Gestor de Recursos Humanos</span>
            </div>
            <div class="feature">
              <span class="feature-icon">✓</span>
              <span>Gestor de Activos y Bienes</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel derecho - Formulario -->
      <div class="login-right">
        <div class="login-card">
          <div class="login-header">
            <div class="logo-icon">🔐</div>
            <h2 class="login-title">INICIO DE SESIÓN</h2>
            <p class="login-subtitle">Ingresa tus credenciales</p>
          </div>

          <form @submit.prevent="handleLogin" class="login-form">
            <!-- Campo Usuario -->
            <div class="form-group">
              <label for="username">
                <span class="label-icon">👤</span>
                Usuario
              </label>
              <input
                id="username"
                v-model="form.username"
                type="text"
                placeholder="Ingresa tu nombre de usuario"
                required
                autocomplete="username"
                :class="{ 'is-invalid': errors.username }"
                :disabled="loading"
                @keyup.enter="handleLogin"
              />
              <span v-if="errors.username" class="error-message">
                {{ errors.username }}
              </span>
            </div>

            <!-- Campo Password -->
            <div class="form-group">
              <label for="password">
                <span class="label-icon">🔑</span>
                Contraseña
              </label>
              <div class="password-input-wrapper">
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Ingresa tu contraseña"
                  required
                  autocomplete="current-password"
                  :class="{ 'is-invalid': errors.password }"
                  :disabled="loading"
                  @keyup.enter="handleLogin"
                />
                <button
                  type="button"
                  class="toggle-password"
                  @click="showPassword = !showPassword"
                  :disabled="loading"
                >
                  <i :class="passwordIcon"></i>
                </button>
              </div>
              <span v-if="errors.password" class="error-message">
                {{ errors.password }}
              </span>
            </div>

            <!-- Opciones adicionales -->
            <div class="form-options">
              <label class="remember-me">
                <input v-model="rememberMe" type="checkbox" :disabled="loading" />
                <span>Recordarme</span>
              </label>
              <a href="#" class="forgot-link" @click.prevent="handleForgotPassword">
                ¿Olvidaste tu contraseña?
              </a>
            </div>

            <!-- Botón Login -->
            <button type="submit" class="btn-login" :disabled="loading || !form.username || !form.password">
              <span v-if="loading" class="spinner"></span>
              <span v-else>INGRESAR</span>
            </button>

            <!-- Mensaje de error global -->
            <div v-if="loginError" class="error-global">
              <i class="bi bi-exclamation-triangle-fill"></i>
              {{ loginError }}
            </div>
          </form>

          <div class="login-footer">
            <p>Unidad de Sistemas &copy; 2026 Concejo Municipal de Potosí. <br>Todos los derechos reservados.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores'

// Router y Store
const router = useRouter()
const authStore = useAuthStore()

// Estado del formulario
const form = reactive({
  username: '',
  password: ''
})

const errors = reactive({
  username: '',
  password: ''
})

const loading = ref(false)
const loginError = ref('')
const rememberMe = ref(false)
const showPassword = ref(false)


// Computed
const isDevelopment = computed(() => import.meta.env.MODE === 'development')

const passwordIcon = computed(() => {
  return showPassword.value ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'
})

// Validar formulario
const validateForm = () => {
  let isValid = true
  errors.username = ''
  errors.password = ''

  // Validar username
  if (!form.username.trim()) {
    errors.username = 'El nombre de usuario es requerido'
    isValid = false
  } else if (form.username.length < 3) {
    errors.username = 'El usuario debe tener al menos 3 caracteres'
    isValid = false
  }

  // Validar password
  if (!form.password) {
    errors.password = 'La contraseña es requerida'
    isValid = false
  } else if (form.password.length < 6) {
    errors.password = 'La contraseña debe tener al menos 6 caracteres'
    isValid = false
  }

  return isValid
}

// Manejar Login
const handleLogin = async () => {
  loginError.value = ''

  if (!validateForm()) {
    return
  }

  loading.value = true

  try {
    // Llamar al store para autenticar
    const result = await authStore.login({
      username: form.username,
      password: form.password
    })

    if (result.success) {
       const token = result.data?.token;
       if (token) {
            localStorage.setItem('auth_token', token);
            // También actualizamos el store para que sepa que está autenticado
            authStore.token = token;
            authStore.isAuthenticated = true;
        }

      // Guardar preferencia de "recordarme"
      if (rememberMe.value) {
        localStorage.setItem('remember_me', 'true')
        // Guardar usuario y contraseña en localStorage
        localStorage.setItem('saved_username', form.username)
        localStorage.setItem('saved_password', form.password)
      } else {
        localStorage.removeItem('remember_me')
        localStorage.removeItem('saved_username')
        localStorage.removeItem('saved_password')
      }

        await new Promise(resolve => setTimeout(resolve, 100))
        await router.replace({ name: 'dashboard' })
    } else {
      // Mostrar error específico
      if (result.errors) {
        if (result.errors.username) {
          errors.username = result.errors.username[0]
        }
        if (result.errors.password) {
          errors.password = result.errors.password[0]
        }
      }
      loginError.value = result.message || 'Error al iniciar sesión'
    }
  } catch (error) {
    console.error('Error en login:', error)

    // Manejar errores específicos
    if (error.response) {
      const status = error.response.status
      const data = error.response.data

      if (status === 422) {
        // Errores de validación
        if (data.errors) {
          if (data.errors.username) {
            errors.username = data.errors.username[0]
          }
          if (data.errors.password) {
            errors.password = data.errors.password[0]
          }
        }
        loginError.value = data.message || 'Datos incorrectos'
      } else if (status === 401) {
        loginError.value = 'Usuario o contraseña incorrectos'
      } else if (status === 403) {
        loginError.value = 'Tu cuenta está inactiva. Contacta al administrador'
      } else if (status === 429) {
        loginError.value = 'Demasiados intentos fallidos. Espera unos minutos'
      } else {
        loginError.value = data.message || 'Error al procesar la solicitud'
      }
    } else if (error.request) {
      loginError.value = 'No se pudo conectar al servidor. Verifica tu conexión'
    } else {
      loginError.value = 'Error inesperado. Intenta nuevamente'
    }
  } finally {
    loading.value = false
  }
}

// Manejar "Olvidé mi contraseña"
const handleForgotPassword = () => {
  // Implementar lógica para recuperar contraseña
  alert('Función de recuperación de contraseña en desarrollo')
}

// Manejar registro
const handleRegister = () => {
  // Implementar lógica de registro
  alert('Función de registro en desarrollo')
}

// Verificar si ya hay sesión activa
const checkAuth = () => {
  if (authStore.isAuthenticated) {
    router.push({ name: 'dashboard' })
  }
}

// Cargar credenciales guardadas
const loadSavedCredentials = () => {
  const remember = localStorage.getItem('remember_me')
  if (remember === 'true') {
    const savedUsername = localStorage.getItem('saved_username')
    const savedPassword = localStorage.getItem('saved_password')

    if (savedUsername) {
      form.username = savedUsername
      rememberMe.value = true
    }

    if (savedPassword) {
      form.password = savedPassword
    }
  }
}

// Guardar/limpiar credenciales cuando cambie "recordarme"
watch(rememberMe, (newValue) => {
  if (!newValue) {
    localStorage.removeItem('saved_username')
    localStorage.removeItem('saved_password')
  } else if (form.username && form.password) {
    localStorage.setItem('saved_username', form.username)
    localStorage.setItem('saved_password', form.password)
  }
})

// Guardar credenciales cuando el usuario ingrese datos y "recordarme" esté activo
watch([() => form.username, () => form.password], ([newUsername, newPassword]) => {
  if (rememberMe.value && newUsername && newPassword) {
    localStorage.setItem('saved_username', newUsername)
    localStorage.setItem('saved_password', newPassword)
  }
})

// Ciclo de vida
onMounted(() => {
  checkAuth()
  loadSavedCredentials()
})
</script>

<style scoped>
/* Reset y variables */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  padding: 20px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.login-wrapper {
  display: flex;
  width: 100%;
  max-width: 1000px;
  min-height: 600px;
  background: white;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Panel izquierdo */
.login-left {
  flex: 1;
  background: linear-gradient(135deg, #cc0000, #8B0000);
  padding: 60px 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  position: relative;
  overflow: hidden;
}

.login-left::before {
  content: '';
  position: absolute;
  width: 300px;
  height: 300px;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 50%;
  top: -100px;
  right: -100px;
}

.login-left::after {
  content: '';
  position: absolute;
  width: 200px;
  height: 200px;
  background: rgba(255, 255, 255, 0.03);
  border-radius: 50%;
  bottom: -50px;
  left: -50px;
}

.brand-content {
  position: relative;
  z-index: 1;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}

.brand-content h1 {
  font-size: 32px;
  font-weight: 700;
  margin-bottom: 12px;
}

.brand-content p {
  opacity: 0.9;
  font-size: 16px;
  line-height: 1.6;
  margin-bottom: 30px;
  text-align: justify;
}

.brand-features {
  display: flex;
  flex-direction: column;
  gap: 12px;
  align-items: flex-start;
  padding-left: 0;
  width: 100%;
  justify-content: center;
}

.feature {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 14px;
}

.feature-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 24px;
  height: 24px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  font-weight: bold;
  font-size: 14px;
}

/* Panel derecho */
.login-right {
  flex: 1;
  padding: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: white;
}

.login-card {
  width: 100%;
  max-width: 380px;
}

.login-header {
  text-align: center;
  margin-bottom: 36px;
}

.logo-icon {
  font-size: 48px;
  margin-bottom: 12px;
  display: block;
}

.login-title {
  font-size: 32px;
  font-weight: 800;
  color: #2c3e50;
  letter-spacing: 2px;
  margin-bottom: 4px;
  background: linear-gradient(135deg, #cc0000, #8B0000);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.login-subtitle {
  color: #7f8c8d;
  font-size: 14px;
}

/* Formulario */
.login-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group label {
  font-size: 13px;
  font-weight: 600;
  color: #34495e;
  display: flex;
  align-items: center;
  gap: 8px;
}

.label-icon {
  font-size: 16px;
}

.form-group input {
  padding: 12px 16px;
  border: 2px solid #e0e0e0;
  border-radius: 12px;
  font-size: 14px;
  transition: all 0.3s ease;
  background: #f8f9fa;
  color: #2c3e50;
}

.form-group input:focus {
  outline: none;
  border-color: #cc0000;
  background: white;
  box-shadow: 0 0 0 4px rgba(204, 0, 0, 0.1);
}

.form-group input.is-invalid {
  border-color: #e74c3c;
  background: #fff5f5;
}

.form-group input.is-invalid:focus {
  box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1);
}

.form-group input:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.error-message {
  color: #e74c3c;
  font-size: 12px;
  margin-top: 2px;
  animation: shake 0.4s ease;
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-6px); }
  75% { transform: translateX(6px); }
}

.password-input-wrapper {
  position: relative;
}

.password-input-wrapper input {
  width: 100%;
  padding-right: 48px;
}

.toggle-password {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
  padding: 4px;
  border-radius: 8px;
  transition: background 0.3s;
  color: #cc0000;
}

.toggle-password:hover:not(:disabled) {
  background: #f0f0f0;
}

.toggle-password:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Opciones */
.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 13px;
}

.remember-me {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  color: #555;
}

.remember-me input[type="checkbox"] {
  width: 16px;
  height: 16px;
  cursor: pointer;
  accent-color: #cc0000;
}

.remember-me input[type="checkbox"]:disabled {
  cursor: not-allowed;
  opacity: 0.6;
}

.forgot-link {
  color: #cc0000;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s;
  cursor: pointer;
}

.forgot-link:hover {
  color: #8B0000;
  text-decoration: underline;
}

/* Botón Login */
.btn-login {
  padding: 14px;
  background: linear-gradient(135deg, #cc0000, #8B0000);
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 700;
  letter-spacing: 2px;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 4px;
  position: relative;
  overflow: hidden;
}

.btn-login::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

.btn-login:hover:not(:disabled)::before {
  left: 100%;
}

.btn-login:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(204, 0, 0, 0.4);
}

.btn-login:active:not(:disabled) {
  transform: translateY(0);
}

.btn-login:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.spinner {
  display: inline-block;
  width: 20px;
  height: 20px;
  border: 3px solid rgba(255,255,255,0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.error-global {
  color: #e74c3c;
  text-align: center;
  font-size: 13px;
  padding: 10px;
  background: #fff5f5;
  border-radius: 8px;
  border: 1px solid #fcc;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

/* Credenciales de desarrollo */
.dev-credentials {
  margin-top: 16px;
  padding: 12px;
  background: #f8f9fa;
  border-radius: 8px;
  border: 1px dashed #dee2e6;
  font-size: 12px;
}

.dev-header {
  font-weight: 600;
  color: #495057;
  margin-bottom: 6px;
  text-align: center;
}

.dev-item {
  display: flex;
  gap: 6px;
  justify-content: center;
  flex-wrap: wrap;
}

.dev-label {
  font-weight: 600;
  color: #6c757d;
}

.dev-value {
  color: #cc0000;
  font-weight: 500;
  font-family: monospace;
}

/* Footer */
.login-footer {
  text-align: center;
  margin-top: 20px;
  padding-top: 16px;
  border-top: 1px solid #f0f0f0;
}

.login-footer p {
  color: #7f8c8d;
  font-size: 14px;
}

.imagenLogo {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  margin-bottom: 1.5rem;
}

.imagenLogo img {
  max-width: 300px;
  height: auto;
  display: block;
}

/* Responsive */
@media (max-width: 768px) {
  .login-wrapper {
    flex-direction: column;
    max-width: 450px;
    min-height: auto;
  }

  .login-left {
    padding: 40px 30px;
    min-height: 250px;
  }

  .login-left h1 {
    font-size: 24px;
  }

  .login-left p {
    font-size: 14px;
  }

  .brand-features {
    padding-left: 0;
    align-items: center;
  }

  .login-right {
    padding: 30px 24px;
  }

  .login-title {
    font-size: 28px;
  }

  .imagenLogo img {
    max-width: 180px;
  }
}

@media (max-width: 480px) {
  .login-container {
    padding: 10px;
  }

  .login-right {
    padding: 24px 16px;
  }

  .login-title {
    font-size: 24px;
  }

  .form-options {
    flex-direction: column;
    gap: 8px;
    align-items: flex-start;
  }

  .imagenLogo img {
    max-width: 140px;
  }

  .dev-item {
    flex-direction: column;
    align-items: center;
  }
}
</style>
