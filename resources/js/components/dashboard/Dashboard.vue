<template>
  <div class="dashboard">
    <!-- Tarjetas de estadísticas -->
    <div class="stats-grid">
      <div class="stat-card" v-for="stat in statistics" :key="stat.title">
        <div class="stat-icon" :style="{ background: stat.color }">
          <i :class="stat.icon"></i>
        </div>
        <div class="stat-info">
          <div class="stat-value">{{ stat.value }}</div>
          <div class="stat-title">{{ stat.title }}</div>
          <div class="stat-change" :class="stat.change > 0 ? 'positive' : 'negative'">
            <i :class="stat.change > 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
            {{ Math.abs(stat.change) }}%
          </div>
        </div>
      </div>
    </div>

    <!-- Gráficos y tablas -->
    <div class="dashboard-grid">
      <!-- Actividad Reciente -->
      <div class="card recent-activity">
        <div class="card-header">
          <h3>Actividad Reciente</h3>
          <button class="btn-link">Ver todas</button>
        </div>
        <div class="card-body">
          <div class="activity-item" v-for="activity in recentActivities" :key="activity.id">
            <div class="activity-icon" :style="{ background: activity.color }">
              <i :class="activity.icon"></i>
            </div>
            <div class="activity-content">
              <p>{{ activity.description }}</p>
              <span>{{ activity.time }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de Usuarios Recientes -->
      <div class="card recent-users">
        <div class="card-header">
          <h3>Usuarios Recientes</h3>
          <button class="btn-link">Ver todos</button>
        </div>
        <div class="card-body">
          <div class="user-item" v-for="user in recentUsers" :key="user.id">
            <div class="user-avatar-small" :style="{ background: user.color }">
              {{ user.initials }}
            </div>
            <div class="user-info">
              <div class="user-name">{{ user.name }}</div>
              <div class="user-email">{{ user.email }}</div>
            </div>
            <span class="user-status" :class="user.status">
              {{ user.status }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../../api/auth';

const authStore = useAuthStore();

// Estadísticas
const statistics = ref([
  {
    title: 'Usuarios Totales',
    value: '1,234',
    icon: 'fas fa-users',
    color: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    change: 12.5
  },
  {
    title: 'Ventas del Mes',
    value: '$45,678',
    icon: 'fas fa-dollar-sign',
    color: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
    change: 8.3
  },
  {
    title: 'Pedidos',
    value: '456',
    icon: 'fas fa-shopping-bag',
    color: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
    change: -2.1
  },
  {
    title: 'Productos',
    value: '789',
    icon: 'fas fa-box',
    color: 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
    change: 15.7
  }
]);

// Actividad reciente
const recentActivities = ref([
  {
    id: 1,
    icon: 'fas fa-user-plus',
    description: 'Nuevo usuario registrado: Juan Pérez',
    time: 'Hace 5 minutos',
    color: '#667eea'
  },
  {
    id: 2,
    icon: 'fas fa-shopping-cart',
    description: 'Nuevo pedido #1234 por $125.00',
    time: 'Hace 15 minutos',
    color: '#f5576c'
  },
  {
    id: 3,
    icon: 'fas fa-edit',
    description: 'Producto "Laptop" actualizado',
    time: 'Hace 1 hora',
    color: '#4facfe'
  },
  {
    id: 4,
    icon: 'fas fa-comment',
    description: 'Nuevo comentario en "iPhone 15"',
    time: 'Hace 2 horas',
    color: '#43e97b'
  }
]);

// Usuarios recientes
const recentUsers = ref([
  {
    id: 1,
    name: 'María González',
    email: 'maria@email.com',
    initials: 'MG',
    status: 'Activo',
    color: '#667eea'
  },
  {
    id: 2,
    name: 'Carlos Rodríguez',
    email: 'carlos@email.com',
    initials: 'CR',
    status: 'Pendiente',
    color: '#f5576c'
  },
  {
    id: 3,
    name: 'Ana Martínez',
    email: 'ana@email.com',
    initials: 'AM',
    status: 'Activo',
    color: '#4facfe'
  },
  {
    id: 4,
    name: 'Luis Sánchez',
    email: 'luis@email.com',
    initials: 'LS',
    status: 'Inactivo',
    color: '#43e97b'
  }
]);

onMounted(() => {
  // Aquí puedes cargar datos desde la API
  console.log('Dashboard montado');
});
</script>

<style scoped>
.dashboard {
  padding: 0;
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 15px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 1.5rem;
  flex-shrink: 0;
}

.stat-info {
  flex: 1;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1a1a2e;
}

.stat-title {
  font-size: 0.85rem;
  color: #999;
  margin-top: 2px;
}

.stat-change {
  font-size: 0.8rem;
  font-weight: 600;
  margin-top: 4px;
}

.stat-change.positive {
  color: #43e97b;
}

.stat-change.negative {
  color: #f5576c;
}

/* Dashboard Grid */
.dashboard-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

/* Cards */
.card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  overflow: hidden;
}

.card-header {
  padding: 15px 20px;
  border-bottom: 1px solid #f0f2f5;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-header h3 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
  color: #1a1a2e;
}

.btn-link {
  background: none;
  border: none;
  color: #667eea;
  font-size: 0.85rem;
  cursor: pointer;
  padding: 5px 10px;
  border-radius: 4px;
  transition: background 0.2s;
}

.btn-link:hover {
  background: #f7fafc;
}

.card-body {
  padding: 15px 20px;
  max-height: 350px;
  overflow-y: auto;
}

.card-body::-webkit-scrollbar {
  width: 4px;
}

.card-body::-webkit-scrollbar-track {
  background: transparent;
}

.card-body::-webkit-scrollbar-thumb {
  background: #e2e8f0;
  border-radius: 2px;
}

/* Activity Items */
.activity-item {
  display: flex;
  gap: 12px;
  padding: 10px 0;
  border-bottom: 1px solid #f7fafc;
}

.activity-item:last-child {
  border-bottom: none;
}

.activity-icon {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 0.9rem;
  flex-shrink: 0;
}

.activity-content {
  flex: 1;
}

.activity-content p {
  margin: 0;
  font-size: 0.9rem;
  color: #333;
}

.activity-content span {
  font-size: 0.75rem;
  color: #999;
}

/* User Items */
.user-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 0;
  border-bottom: 1px solid #f7fafc;
}

.user-item:last-child {
  border-bottom: none;
}

.user-avatar-small {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-weight: bold;
  font-size: 0.9rem;
  flex-shrink: 0;
}

.user-info {
  flex: 1;
}

.user-name {
  font-size: 0.9rem;
  font-weight: 500;
  color: #1a1a2e;
}

.user-email {
  font-size: 0.8rem;
  color: #999;
}

.user-status {
  font-size: 0.7rem;
  padding: 3px 10px;
  border-radius: 12px;
  font-weight: 500;
}

.user-status.Activo {
  background: #d4edda;
  color: #155724;
}

.user-status.Pendiente {
  background: #fff3cd;
  color: #856404;
}

.user-status.Inactivo {
  background: #f8d7da;
  color: #721c24;
}

/* Responsive */
@media (max-width: 1024px) {
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 480px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>
