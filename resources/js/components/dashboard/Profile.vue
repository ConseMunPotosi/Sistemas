<template>
  <div class="profile-page">
    <div class="profile-container">
      <div class="profile-header">
        <h1 class="profile-title">Perfil personal</h1>
        <p class="profile-subtitle">Gestiona tu información personal y configuración de cuenta</p>
      </div>
      <div class="profile-grid">
        <!-- ========================================== -->
        <!-- COLUMNA IZQUIERDA - AVATAR Y DATOS BÁSICOS -->
        <!-- ========================================== -->
        <div class="profile-card profile-info-card">
            <div class="avatar-section">
            <div class="avatar-container">
                <div class="avatar-placeholder">
                <span class="avatar-text">{{ userInitials }}</span>
                </div>
                <button class="avatar-edit-btn" title="Cambiar foto">
                <span class="edit-icon">📷</span>
                </button>
            </div>
            <h2 class="user-fullname">{{ user?.displayName || user?.nombre_completo || 'Usuario' }}</h2>
            <p class="user-role-badge">{{ userRoles }}</p>
            <p class="user-email">{{ user?.correo || user?.email || 'usuario@ejemplo.com' }}</p>
            </div>

            <div class="info-divider"></div>

            <!-- Información básica -->
            <div class="info-section">
            <div class="info-item">
                <span class="info-label">👤 Usuario</span>
                <span class="info-value">{{ user?.usuario || 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">📧 Correo</span>
                <span class="info-value">{{ user?.correo || user?.email || 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">📅 Miembro desde</span>
                <span class="info-value">{{ formatDate(user?.fecha_creacion || user?.created_at) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">🔄 Último acceso</span>
                <span class="info-value">{{ formatDate(user?.ultimo_acceso || user?.last_login) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">📊 Estado</span>
                <span class="info-value status-badge" :class="user?.activo ? 'status-active' : 'status-inactive'">
                {{ user?.activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- COLUMNA DERECHA - PESTAÑAS                -->
        <!-- ========================================== -->
        <div class="profile-tabs-container">
            <!-- Pestañas -->
            <div class="tabs-header">
            <button
                class="tab-btn"
                :class="{ active: activeTab === 'info' }"
                @click="activeTab = 'info'"
            >
                📋 Información Personal
            </button>
            <button
                class="tab-btn"
                :class="{ active: activeTab === 'password' }"
                @click="activeTab = 'password'"
            >
                🔒 Cambiar Contraseña
            </button>
            <button
                class="tab-btn"
                :class="{ active: activeTab === 'preferences' }"
                @click="activeTab = 'preferences'"
            >
                ⚙️ Preferencias
            </button>
            </div>

            <!-- Contenido de las pestañas -->
            <div class="tab-content">
            <!-- Pestaña: Información Personal -->
            <div v-if="activeTab === 'info'" class="tab-panel">
                <form @submit.prevent="updateProfile" class="profile-form">
                <div class="form-row">
                    <div class="form-group">
                    <label for="displayName">Nombre Completo</label>
                    <input
                        id="displayName"
                        v-model="profileForm.displayName"
                        type="text"
                        placeholder="Tu nombre completo"
                        required
                    />
                    </div>
                    <div class="form-group">
                    <label for="username">Nombre de Usuario</label>
                    <input
                        id="username"
                        v-model="profileForm.username"
                        type="text"
                        placeholder="Tu nombre de usuario"
                        disabled
                    />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input
                        id="email"
                        v-model="profileForm.email"
                        type="email"
                        placeholder="tu@email.com"
                        required
                    />
                    </div>
                    <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input
                        id="phone"
                        v-model="profileForm.phone"
                        type="tel"
                        placeholder="+591 7XXXXXXX"
                    />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                    <label for="cargo">Cargo</label>
                    <input
                        id="cargo"
                        :value="user?.funcionario?.cargo?.nombre || 'Sin cargo'"
                        type="text"
                        disabled
                    />
                    </div>
                    <div class="form-group">
                    <label for="unidad">Unidad</label>
                    <input
                        id="unidad"
                        :value="user?.funcionario?.unidad?.nombre || 'Sin unidad'"
                        type="text"
                        disabled
                    />
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save" :disabled="saving">
                    {{ saving ? 'Guardando...' : 'Guardar Cambios' }}
                    </button>
                    <button type="button" class="btn-cancel" @click="resetForm">Cancelar</button>
                </div>

                <div v-if="updateMessage" class="form-message" :class="updateType">
                    {{ updateMessage }}
                </div>
                </form>
            </div>

            <!-- Pestaña: Cambiar Contraseña -->
            <div v-if="activeTab === 'password'" class="tab-panel">
                <form @submit.prevent="changePassword" class="profile-form">
                <div class="form-group">
                    <label for="currentPassword">Contraseña Actual</label>
                    <div class="password-input-wrapper">
                    <input
                        id="currentPassword"
                        v-model="passwordForm.current"
                        :type="showCurrentPassword ? 'text' : 'password'"
                        placeholder="Ingresa tu contraseña actual"
                        required
                    />
                    <button
                        type="button"
                        class="password-toggle"
                        @click="showCurrentPassword = !showCurrentPassword"
                    >
                        {{ showCurrentPassword ? '🙈' : '👁️' }}
                    </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="newPassword">Nueva Contraseña</label>
                    <div class="password-input-wrapper">
                    <input
                        id="newPassword"
                        v-model="passwordForm.new"
                        :type="showNewPassword ? 'text' : 'password'"
                        placeholder="Ingresa tu nueva contraseña"
                        required
                        minlength="6"
                    />
                    <button
                        type="button"
                        class="password-toggle"
                        @click="showNewPassword = !showNewPassword"
                    >
                        {{ showNewPassword ? '🙈' : '👁️' }}
                    </button>
                    </div>
                    <div class="password-strength" v-if="passwordForm.new">
                    <span class="strength-label">Fortaleza:</span>
                    <div class="strength-bar">
                        <div class="strength-fill" :class="passwordStrength" :style="{ width: passwordStrengthPercent + '%' }"></div>
                    </div>
                    <span class="strength-text">{{ passwordStrengthText }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirmar Nueva Contraseña</label>
                    <div class="password-input-wrapper">
                    <input
                        id="confirmPassword"
                        v-model="passwordForm.confirm"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        placeholder="Confirma tu nueva contraseña"
                        required
                        minlength="6"
                    />
                    <button
                        type="button"
                        class="password-toggle"
                        @click="showConfirmPassword = !showConfirmPassword"
                    >
                        {{ showConfirmPassword ? '🙈' : '👁️' }}
                    </button>
                    </div>
                    <span v-if="passwordForm.new && passwordForm.confirm && passwordForm.new !== passwordForm.confirm" class="password-error">
                    ⚠️ Las contraseñas no coinciden
                    </span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save" :disabled="changingPassword">
                    {{ changingPassword ? 'Actualizando...' : 'Actualizar Contraseña' }}
                    </button>
                    <button type="button" class="btn-cancel" @click="resetPasswordForm">Cancelar</button>
                </div>

                <div v-if="passwordMessage" class="form-message" :class="passwordType">
                    {{ passwordMessage }}
                </div>
                </form>
            </div>

            <!-- Pestaña: Preferencias -->
            <div v-if="activeTab === 'preferences'" class="tab-panel">
                <div class="preferences-section">
                <h3>🎨 Tema de la Aplicación</h3>
                <div class="theme-options">
                    <button
                    class="theme-btn"
                    :class="{ active: selectedTheme === 'light' }"
                    @click="selectedTheme = 'light'"
                    >
                    ☀️ Claro
                    </button>
                    <button
                    class="theme-btn"
                    :class="{ active: selectedTheme === 'dark' }"
                    @click="selectedTheme = 'dark'"
                    >
                    🌙 Oscuro
                    </button>
                    <button
                    class="theme-btn"
                    :class="{ active: selectedTheme === 'system' }"
                    @click="selectedTheme = 'system'"
                    >
                    💻 Sistema
                    </button>
                </div>
                </div>

                <div class="preferences-section">
                <h3>🔔 Notificaciones</h3>
                <div class="preference-item">
                    <label class="toggle-switch">
                    <input type="checkbox" v-model="preferences.emailNotifications" />
                    <span class="toggle-slider"></span>
                    </label>
                    <span class="preference-label">Notificaciones por correo</span>
                </div>
                <div class="preference-item">
                    <label class="toggle-switch">
                    <input type="checkbox" v-model="preferences.browserNotifications" />
                    <span class="toggle-slider"></span>
                    </label>
                    <span class="preference-label">Notificaciones en navegador</span>
                </div>
                <div class="preference-item">
                    <label class="toggle-switch">
                    <input type="checkbox" v-model="preferences.soundNotifications" />
                    <span class="toggle-slider"></span>
                    </label>
                    <span class="preference-label">Sonido de notificaciones</span>
                </div>
                </div>

                <div class="preferences-section">
                <h3>🌐 Idioma</h3>
                <select v-model="preferences.language" class="language-select">
                    <option value="es">Español</option>
                    <option value="en">English</option>
                    <option value="pt">Português</option>
                </select>
                </div>

                <div class="form-actions">
                <button class="btn-save" @click="savePreferences">Guardar Preferencias</button>
                </div>
            </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue';
import { useAuthStore } from '../../api/auth.js';

// Store
const authStore = useAuthStore();

// ==========================================
// ESTADO
// ==========================================
const activeTab = ref('info');
const saving = ref(false);
const changingPassword = ref(false);
const updateMessage = ref('');
const updateType = ref('success');
const passwordMessage = ref('');
const passwordType = ref('success');

// Mostrar contraseñas
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

// Tema
const selectedTheme = ref('light');

// ==========================================
// USUARIO
// ==========================================
const user = computed(() => authStore.user);

// Iniciales del usuario
const userInitials = computed(() => {
  const name = user.value?.displayName || user.value?.nombre_completo || user.value?.usuario || 'U';
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
});

// Roles del usuario
const userRoles = computed(() => {
  if (!user.value?.roles) return 'Sin roles';
  return user.value.roles.map(r => r.nombre).join(', ');
});

// ==========================================
// FORMULARIO DE PERFIL
// ==========================================
const profileForm = reactive({
  displayName: '',
  username: '',
  email: '',
  phone: ''
});

// ==========================================
// FORMULARIO DE CONTRASEÑA
// ==========================================
const passwordForm = reactive({
  current: '',
  new: '',
  confirm: ''
});

// ==========================================
// PREFERENCIAS
// ==========================================
const preferences = reactive({
  emailNotifications: true,
  browserNotifications: true,
  soundNotifications: false,
  language: 'es'
});

// ==========================================
// MÉTODOS
// ==========================================
const formatDate = (date) => {
  if (!date) return 'N/A';
  const d = new Date(date);
  return d.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const resetForm = () => {
  loadUserData();
  updateMessage.value = '';
};

const loadUserData = () => {
  const userData = user.value;
  if (userData) {
    profileForm.displayName = userData.displayName || userData.nombre_completo || '';
    profileForm.username = userData.usuario || '';
    profileForm.email = userData.correo || userData.email || '';
    profileForm.phone = userData.telefono || userData.phone || '';
  }
};

const updateProfile = async () => {
  saving.value = true;
  updateMessage.value = '';

  try {
    // Simular actualización
    await new Promise(resolve => setTimeout(resolve, 1000));

    // Aquí iría la llamada a la API
    // await api.put('/profile', profileForm);

    updateMessage.value = '✅ Perfil actualizado exitosamente';
    updateType.value = 'success';
  } catch (error) {
    updateMessage.value = '❌ Error al actualizar el perfil';
    updateType.value = 'error';
  } finally {
    saving.value = false;
  }
};

// ==========================================
// CAMBIAR CONTRASEÑA
// ==========================================
const passwordStrength = computed(() => {
  const pass = passwordForm.new;
  if (!pass) return 'weak';
  if (pass.length >= 12 && /[A-Z]/.test(pass) && /[0-9]/.test(pass) && /[^A-Za-z0-9]/.test(pass)) {
    return 'strong';
  }
  if (pass.length >= 8 && /[A-Z]/.test(pass) && /[0-9]/.test(pass)) {
    return 'medium';
  }
  return 'weak';
});

const passwordStrengthPercent = computed(() => {
  switch(passwordStrength.value) {
    case 'strong': return 100;
    case 'medium': return 60;
    default: return 30;
  }
});

const passwordStrengthText = computed(() => {
  switch(passwordStrength.value) {
    case 'strong': return 'Fuerte';
    case 'medium': return 'Media';
    default: return 'Débil';
  }
});

const changePassword = async () => {
  if (passwordForm.new !== passwordForm.confirm) {
    passwordMessage.value = '⚠️ Las contraseñas no coinciden';
    passwordType.value = 'error';
    return;
  }

  if (passwordForm.new.length < 6) {
    passwordMessage.value = '⚠️ La contraseña debe tener al menos 6 caracteres';
    passwordType.value = 'error';
    return;
  }

  changingPassword.value = true;
  passwordMessage.value = '';

  try {
    // Simular actualización
    await new Promise(resolve => setTimeout(resolve, 1000));

    // Aquí iría la llamada a la API
    // await api.post('/change-password', {
    //   current_password: passwordForm.current,
    //   new_password: passwordForm.new
    // });

    passwordMessage.value = '✅ Contraseña actualizada exitosamente';
    passwordType.value = 'success';
    resetPasswordForm();
  } catch (error) {
    passwordMessage.value = '❌ Error al actualizar la contraseña';
    passwordType.value = 'error';
  } finally {
    changingPassword.value = false;
  }
};

const resetPasswordForm = () => {
  passwordForm.current = '';
  passwordForm.new = '';
  passwordForm.confirm = '';
  passwordMessage.value = '';
};

// ==========================================
// PREFERENCIAS
// ==========================================
const savePreferences = () => {
  // Guardar preferencias en localStorage
  localStorage.setItem('user_preferences', JSON.stringify(preferences));
  localStorage.setItem('theme', selectedTheme.value);

  // Aplicar tema
  applyTheme(selectedTheme.value);

  alert('✅ Preferencias guardadas correctamente');
};

const applyTheme = (theme) => {
  if (theme === 'dark') {
    document.documentElement.classList.add('dark-theme');
  } else {
    document.documentElement.classList.remove('dark-theme');
  }
};

// ==========================================
// CICLO DE VIDA
// ==========================================
onMounted(() => {
  console.log('🔍 Profile - Usuario:', user.value);
  loadUserData();

  // Cargar preferencias guardadas
  const savedPrefs = localStorage.getItem('user_preferences');
  if (savedPrefs) {
    const parsed = JSON.parse(savedPrefs);
    Object.assign(preferences, parsed);
  }

  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
    selectedTheme.value = savedTheme;
    applyTheme(savedTheme);
  }
});
</script>

<style scoped>
/* ==========================================
   PROFILE - ESTILOS PRINCIPALES
   ========================================== */
html, body, #app, .profile-page {
  height: 100%;
  min-height: 100vh;
  min-height: 100dvh;
}

.profile-page {
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  padding: 20px;
  margin: -20px;
  height: auto;
  min-height: 100vh;
}

.profile-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  min-height: 100vh;
}

.profile-header {
  margin-bottom: 30px;
}

.profile-title {
  font-size: 28px;
  font-weight: 700;
  color: #cc0000;
  margin: 0 0 5px 0;
}

.profile-subtitle {
  color: #6b7280;
  font-size: 16px;
  margin: 0;
}

/* ==========================================
   GRID
   ========================================== */
.profile-grid {
  display: grid;
  grid-template-columns: 340px 1fr;
  gap: 24px;
}

/* ==========================================
   TARJETAS
   ========================================== */
.profile-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
  padding: 24px;
}

/* ==========================================
   AVATAR
   ========================================== */
.avatar-section {
  text-align: center;
  padding-bottom: 20px;
}

.avatar-container {
  position: relative;
  display: inline-block;
}

.avatar-placeholder {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background: linear-gradient(to bottom, #cc0000 0%, #8B0000 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 40px;
  font-weight: 700;
  color: white;
  margin: 0 auto;
}

.avatar-edit-btn {
  position: absolute;
  bottom: 4px;
  right: 4px;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: linear-gradient(to bottom, #cc0000 0%, #8B0000 100%);
  border: 3px solid white;
  color: white;
  cursor: pointer;
  font-size: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s;
}

.avatar-edit-btn:hover {
  background: linear-gradient(to bottom, #cc0000 0%, #8B0000 100%);
  transform: scale(1.05);
}

.edit-icon {
  font-size: 16px;
}

.user-fullname {
  font-size: 20px;
  font-weight: 600;
  color: #1a1a2e;
  margin: 12px 0 4px 0;
}

.user-role-badge {
  display: inline-block;
  background: #eff6ff;
  color: #cc0000;
  padding: 4px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 500;
  margin: 4px 0 8px 0;
}

.user-email {
  color: #6b7280;
  font-size: 14px;
  margin: 0;
}

.info-divider {
  height: 1px;
  background: #e5e7eb;
  margin: 16px 0;
}

.info-section {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.info-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 4px 0;
}

.info-label {
  color: #6b7280;
  font-size: 14px;
}

.info-value {
  color: #1a1a2e;
  font-size: 14px;
  font-weight: 500;
}

.status-badge {
  padding: 2px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.status-active {
  background: #d1fae5;
  color: #065f46;
}

.status-inactive {
  background: #fef2f2;
  color: #991b1b;
}

/* ==========================================
   PESTAÑAS
   ========================================== */
.profile-tabs-container {
  background: white;
  border-radius: 16px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

.tabs-header {
  display: flex;
  border-bottom: 1px solid #e5e7eb;
  background: #f9fafb;
}

.tab-btn {
  padding: 16px 24px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  color: #6b7280;
  transition: all 0.3s;
  position: relative;
}

.tab-btn:hover {
  color: #1a1a2e;
  background: rgba(79, 70, 229, 0.05);
}

.tab-btn.active {
  color: #cc0000;
  background: white;
}

.tab-btn.active::after {
  content: '';
  position: absolute;
  bottom: -1px;
  left: 0;
  right: 0;
  height: 2px;
  background: #c00000;
}

.tab-content {
  padding: 24px;
}

.tab-panel {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ==========================================
   FORMULARIOS
   ========================================== */
.profile-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.form-group label {
  font-size: 14px;
  font-weight: 500;
  color: #374151;
}

.form-group input,
.form-group select {
  padding: 10px 14px;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.3s;
  background: #f9fafb;
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #cc0000;
  box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

.form-group input:disabled {
  background: #f3f4f6;
  color: #6b7280;
  cursor: not-allowed;
}

.password-input-wrapper {
  position: relative;
}

.password-input-wrapper input {
  width: 100%;
  padding-right: 44px;
}

.password-toggle {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  font-size: 18px;
  cursor: pointer;
  padding: 4px;
}

.password-strength {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 4px;
  font-size: 13px;
}

.strength-label {
  color: #6b7280;
}

.strength-bar {
  flex: 1;
  height: 4px;
  background: #e5e7eb;
  border-radius: 4px;
  overflow: hidden;
}

.strength-fill {
  height: 100%;
  border-radius: 4px;
  transition: width 0.3s ease;
}

.strength-fill.weak {
  background: #ef4444;
}

.strength-fill.medium {
  background: #f59e0b;
}

.strength-fill.strong {
  background: #22c55e;
}

.strength-text {
  color: #6b7280;
  font-size: 12px;
  min-width: 40px;
}

.password-error {
  color: #ef4444;
  font-size: 13px;
  margin-top: 4px;
}

.form-actions {
  display: flex;
  gap: 12px;
  padding-top: 8px;
}

.btn-save {
  padding: 10px 32px;
  background: #cc0000;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-save:hover:not(:disabled) {
  background: #cc0000;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px #cc0000

}

.btn-save:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-cancel {
  padding: 10px 24px;
  background: #f3f4f6;
  color: #374151;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-cancel:hover {
  background: #e5e7eb;
}

.form-message {
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 14px;
}

.form-message.success {
  background: #d1fae5;
  color: #065f46;
}

.form-message.error {
  background: #fef2f2;
  color: #991b1b;
}

/* ==========================================
   PREFERENCIAS
   ========================================== */
.preferences-section {
  margin-bottom: 28px;
}

.preferences-section h3 {
  font-size: 16px;
  font-weight: 600;
  color: #1a1a2e;
  margin: 0 0 12px 0;
}

.theme-options {
  display: flex;
  gap: 12px;
}

.theme-btn {
  padding: 10px 20px;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  background: white;
  cursor: pointer;
  font-size: 14px;
  transition: all 0.3s;
}

.theme-btn:hover {
  border-color: #cc0000;
}

.theme-btn.active {
  border-color: #cc0000;
  background: #eff6ff;
  color: #cc0000;
}

.preference-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 0;
}

.toggle-switch {
  position: relative;
  display: inline-block;
  width: 48px;
  height: 26px;
  flex-shrink: 0;
  cursor: pointer;
}

.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle-slider {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #d1d5db;
  border-radius: 26px;
  transition: all 0.3s;
}

.toggle-slider::before {
  content: '';
  position: absolute;
  width: 20px;
  height: 20px;
  left: 3px;
  bottom: 3px;
  background: white;
  border-radius: 50%;
  transition: all 0.3s;
}

.toggle-switch input:checked + .toggle-slider {
  background: #cc0000;
}

.toggle-switch input:checked + .toggle-slider::before {
  transform: translateX(22px);
}

.preference-label {
  font-size: 14px;
  color: #374151;
}

.language-select {
  padding: 10px 14px;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 14px;
  background: #f9fafb;
  min-width: 200px;
}

.language-select:focus {
  outline: none;
  border-color: #cc0000;
}

/* ==========================================
   RESPONSIVE
   ========================================== */
@media (max-width: 1024px) {
  .profile-grid {
    grid-template-columns: 1fr;
  }

  .profile-info-card {
    max-width: 500px;
    margin: 0 auto;
  }
}

@media (max-width: 640px) {
  .profile-container {
    padding: 12px;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .tabs-header {
    flex-direction: column;
  }

  .tab-btn {
    text-align: center;
    padding: 12px 16px;
  }

  .tab-btn.active::after {
    bottom: 0;
    height: 2px;
  }

  .theme-options {
    flex-wrap: wrap;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn-save,
  .btn-cancel {
    width: 100%;
    text-align: center;
    justify-content: center;
  }
}
</style>
