<template>

  <div class="dashboard-container">

    <!-- =========================================================
         STATS CARDS
         ========================================================= -->
    <div class="stats-grid">

      <div
        class="stat-card"
        v-for="stat in stats"
        :key="stat.title"
      >

        <div
          class="stat-icon"
          :style="{ background: stat.color }"
        >
          <i :class="stat.icon"></i>
        </div>


        <div class="stat-content">

          <h3>
            {{ stat.value }}
          </h3>

          <p>
            {{ stat.title }}
          </p>

          <span
            class="stat-change"
            :class="stat.change > 0 ? 'positive' : 'negative'"
          >

            <i
              :class="
                stat.change > 0
                  ? 'fas fa-arrow-up'
                  : 'fas fa-arrow-down'
              "
            ></i>

            {{ Math.abs(stat.change) }}%

          </span>

        </div>

      </div>

    </div>


    <!-- =========================================================
         ACCESOS RÁPIDOS
         ========================================================= -->
    <div class="quick-access-section">

      <div class="quick-section-title">

        <h3>
          Accesos rápidos
        </h3>

        <p>
          Accede rápidamente a los módulos administrativos.
        </p>

      </div>


      <div class="quick-access-grid">

        <!-- Gestión de Sesiones -->
        <div class="quick-card">

          <div class="quick-icon">
            <i class="fas fa-video"></i>
          </div>


          <div class="quick-content">

            <h3>
              Gestión de Sesiones
            </h3>

            <p>
              Administrar sesiones del Concejo Municipal
              y sus videos.
            </p>

          </div>


          <router-link
            to="/dashboard/gestion-sesiones"
            class="quick-button"
          >

            <span>
              Ingresar
            </span>

            <i class="fas fa-arrow-right"></i>

          </router-link>

        </div>

      </div>

    </div>


    <!-- =========================================================
         CHARTS
         ========================================================= -->
    <div class="charts-grid">

      <!-- Proyectos por Estado -->
      <div class="chart-card">

        <div class="card-header">

          <h3>
            Proyectos por Estado
          </h3>


          <select
            v-model="selectedPeriod"
            class="period-select"
          >

            <option value="month">
              Este Mes
            </option>

            <option value="quarter">
              Este Trimestre
            </option>

            <option value="year">
              Este Año
            </option>

          </select>

        </div>


        <div class="chart-container">

          <canvas
            ref="statusChart"
          ></canvas>

        </div>

      </div>


      <!-- Distribución por Área -->
      <div class="chart-card">

        <div class="card-header">

          <h3>
            Distribución por Área
          </h3>


          <button
            class="btn-refresh"
            @click="refreshData"
          >

            <i class="fas fa-sync-alt"></i>

          </button>

        </div>


        <div class="chart-container">

          <canvas
            ref="areaChart"
          ></canvas>

        </div>

      </div>

    </div>


    <!-- =========================================================
         ACTIVIDAD RECIENTE
         ========================================================= -->
    <div class="activity-section">

      <div class="section-header">

        <h3>
          Actividad Reciente
        </h3>

        <button class="btn-view-all">
          Ver Todos
        </button>

      </div>


      <div class="table-container">

        <table class="activity-table">

          <thead>

            <tr>

              <th>
                Fecha
              </th>

              <th>
                Proyecto
              </th>

              <th>
                Estado
              </th>

              <th>
                Responsable
              </th>

              <th>
                Acciones
              </th>

            </tr>

          </thead>


          <tbody>

            <tr
              v-for="activity in recentActivities"
              :key="activity.id"
            >

              <td>
                {{ activity.date }}
              </td>

              <td>
                {{ activity.project }}
              </td>

              <td>

                <span
                  class="status-badge"
                  :class="activity.statusClass"
                >
                  {{ activity.status }}
                </span>

              </td>

              <td>
                {{ activity.responsible }}
              </td>

              <td>

                <button
                  class="btn-action"
                  @click="viewDetails(activity.id)"
                >

                  <i class="fas fa-eye"></i>

                </button>

              </td>

            </tr>

          </tbody>

        </table>

      </div>

    </div>

  </div>

</template>


<script>

import {
  ref,
  onMounted,
  computed
} from 'vue';

import {
  Chart,
  registerables
} from 'chart.js';

import api from '@/api/axios.js';

Chart.register(...registerables);


export default {

  name: 'DashboardConcejo',


  setup() {

    // =========================================================
    // STATE
    // =========================================================

    const statusChart = ref(null);

    const areaChart = ref(null);

    const selectedPeriod = ref('month');

    const sesionesRealizadas = ref(0);


    const notifications = ref([

      {
        id: 1,
        message: 'Nuevo proyecto registrado'
      },

      {
        id: 2,
        message: 'Sesión programada para mañana'
      },

      {
        id: 3,
        message: 'Documento pendiente de revisión'
      }

    ]);


    // =========================================================
    // COMPUTED
    // =========================================================

    const currentDate = computed(() => {

      return new Date().toLocaleDateString(
        'es-BO',
        {
          weekday: 'long',
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        }
      );

    });


    // =========================================================
    // STATS
    // =========================================================

    const stats = computed(() => [

      {
        title: 'Proyectos Activos',

        value: '42',

        icon: 'fas fa-file-alt',

        color:
          'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',

        change: 12
      },


      {
        title: 'Sesiones Realizadas',

        value: sesionesRealizadas.value,

        icon: 'fas fa-calendar-check',

        color:
          'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',

        change: 8
      },


      {
        title: 'Concejales Activos',

        value: '11',

        icon: 'fas fa-users',

        color:
          'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',

        change: 0
      },


      {
        title: 'Documentos Pendientes',

        value: '7',

        icon: 'fas fa-clock',

        color:
          'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',

        change: -5
      }

    ]);


    // =========================================================
    // ACTIVIDAD RECIENTE
    // =========================================================

    const recentActivities = ref([

      {
        id: 1,
        date: '2024-01-15',
        project: 'Ley de Movilidad Urbana',
        status: 'En Revisión',
        statusClass: 'status-review',
        responsible: 'Lic. Pérez'
      },


      {
        id: 2,
        date: '2024-01-14',
        project: 'Presupuesto Participativo',
        status: 'Aprobado',
        statusClass: 'status-approved',
        responsible: 'Dr. Ramírez'
      },


      {
        id: 3,
        date: '2024-01-13',
        project: 'Plan de Desarrollo Municipal',
        status: 'Pendiente',
        statusClass: 'status-pending',
        responsible: 'Arq. Flores'
      },


      {
        id: 4,
        date: '2024-01-12',
        project: 'Reglamento de Construcción',
        status: 'En Discusión',
        statusClass: 'status-discussion',
        responsible: 'Ing. Torres'
      }

    ]);


    // =========================================================
    // CARGAR SESIONES REALES
    // =========================================================

    const cargarSesiones = async () => {

      try {

        const response = await api.get('/sesiones');

        if (
          response.data &&
          Array.isArray(response.data.data)
        ) {

          sesionesRealizadas.value =
            response.data.data.length;

        } else if (
          Array.isArray(response.data)
        ) {

          sesionesRealizadas.value =
            response.data.length;

        } else {

          sesionesRealizadas.value = 0;

        }

        console.log(
          '✅ Sesiones cargadas:',
          sesionesRealizadas.value
        );

      } catch (error) {

        console.error(
          '❌ Error al cargar sesiones:',
          error
        );

        sesionesRealizadas.value = 0;

      }

    };


    // =========================================================
    // CHARTS
    // =========================================================

    const initCharts = () => {


      // =======================================================
      // STATUS CHART
      // =======================================================

      if (statusChart.value) {

        new Chart(
          statusChart.value,
          {

            type: 'doughnut',

            data: {

              labels: [

                'En Revisión',

                'Aprobado',

                'Pendiente',

                'En Discusión'

              ],

              datasets: [

                {

                  data: [
                    12,
                    19,
                    7,
                    4
                  ],

                  backgroundColor: [

                    'rgba(102, 126, 234, 0.8)',

                    'rgba(75, 192, 192, 0.8)',

                    'rgba(255, 159, 64, 0.8)',

                    'rgba(255, 99, 132, 0.8)'

                  ],

                  borderWidth: 1

                }

              ]

            },


            options: {

              responsive: true,

              maintainAspectRatio: false,

              plugins: {

                legend: {

                  position: 'bottom'

                }

              }

            }

          }
        );

      }


      // =======================================================
      // AREA CHART
      // =======================================================

      if (areaChart.value) {

        new Chart(
          areaChart.value,
          {

            type: 'bar',

            data: {

              labels: [

                'Infraestructura',

                'Social',

                'Económico',

                'Ambiental',

                'Educación'

              ],


              datasets: [

                {

                  label: 'Proyectos por Área',

                  data: [
                    15,
                    12,
                    8,
                    5,
                    8
                  ],

                  backgroundColor: [

                    'rgba(54, 162, 235, 0.7)',

                    'rgba(255, 99, 132, 0.7)',

                    'rgba(255, 206, 86, 0.7)',

                    'rgba(75, 192, 192, 0.7)',

                    'rgba(153, 102, 255, 0.7)'

                  ],

                  borderColor: [

                    'rgba(54, 162, 235, 1)',

                    'rgba(255, 99, 132, 1)',

                    'rgba(255, 206, 86, 1)',

                    'rgba(75, 192, 192, 1)',

                    'rgba(153, 102, 255, 1)'

                  ],

                  borderWidth: 1

                }

              ]

            },


            options: {

              responsive: true,

              maintainAspectRatio: false,

              plugins: {

                legend: {

                  display: false

                }

              },


              scales: {

                y: {

                  beginAtZero: true,

                  ticks: {

                    stepSize: 1

                  }

                }

              }

            }

          }
        );

      }

    };


    // =========================================================
    // MÉTODOS
    // =========================================================

    const toggleNotifications = () => {

      console.log(
        'Toggle notifications'
      );

    };


    const refreshData = () => {

      console.log(
        'Refreshing data...'
      );

      cargarSesiones();

    };


    const viewDetails = (id) => {

      console.log(
        'Viewing details for:',
        id
      );

    };


    // =========================================================
    // LIFECYCLE
    // =========================================================

    onMounted(async () => {

      // Cargar sesiones reales
      await cargarSesiones();

      // Inicializar gráficos
      initCharts();

    });


    // =========================================================
    // RETURN
    // =========================================================

    return {

      stats,

      recentActivities,

      notifications,

      currentDate,

      selectedPeriod,

      statusChart,

      areaChart,

      toggleNotifications,

      refreshData,

      viewDetails

    };

  }

};

</script>


<style scoped>

/* =========================================================
   DASHBOARD
   ========================================================= */

.dashboard-container {

  padding: 20px;

  background: #f5f7fa;

  width: 100%;

  height: 100%;

  box-sizing: border-box;

  font-family:
    'Segoe UI',
    Tahoma,
    Geneva,
    Verdana,
    sans-serif;

  max-width: 100%;

  margin: 0 auto;

}


.dashboard-container > *,

.dashboard-container .chart-container,

.dashboard-container canvas {

  max-width: 100% !important;

  box-sizing: border-box !important;

}


/* =========================================================
   CHART CONTAINER
   ========================================================= */

.chart-container {

  position: relative;

  height: 200px;

  width: 100%;

}


/* =========================================================
   STATS GRID
   ========================================================= */

.stats-grid {

  display: grid;

  grid-template-columns:
    repeat(auto-fit, minmax(220px, 1fr));

  gap: 20px;

  margin-bottom: 30px;

}


.stat-card {

  background: white;

  border-radius: 12px;

  padding: 20px;

  display: flex;

  align-items: center;

  gap: 15px;

  box-shadow:
    0 2px 10px rgba(0, 0, 0, 0.08);

  transition:
    transform 0.2s,
    box-shadow 0.2s;

}


.stat-card:hover {

  transform: translateY(-2px);

  box-shadow:
    0 4px 15px rgba(0, 0, 0, 0.12);

}


.stat-icon {

  width: 50px;

  height: 50px;

  border-radius: 10px;

  display: flex;

  align-items: center;

  justify-content: center;

  color: white;

  font-size: 20px;

}


.stat-content h3 {

  margin: 0;

  font-size: 24px;

  font-weight: bold;

  color: #2d3748;

}


.stat-content p {

  margin: 0;

  color: #718096;

  font-size: 14px;

}


.stat-change {

  font-size: 12px;

  font-weight: 600;

}


.stat-change.positive {

  color: #38a169;

}


.stat-change.negative {

  color: #e53e3e;

}


/* =========================================================
   ACCESOS RÁPIDOS
   ========================================================= */

.quick-access-section {

  margin-bottom: 30px;

}


.quick-section-title {

  margin-bottom: 15px;

}


.quick-section-title h3 {

  margin: 0 0 5px;

  font-size: 20px;

  color: #2d3748;

  font-weight: 700;

}


.quick-section-title p {

  margin: 0;

  color: #718096;

  font-size: 14px;

}


.quick-access-grid {

  display: grid;

  grid-template-columns:
    repeat(auto-fit, minmax(320px, 1fr));

  gap: 20px;

}


.quick-card {

  background: white;

  border-radius: 12px;

  padding: 20px;

  display: flex;

  align-items: center;

  gap: 18px;

  box-shadow:
    0 2px 10px rgba(0, 0, 0, 0.08);

  transition:
    transform 0.2s,
    box-shadow 0.2s;

}


.quick-card:hover {

  transform: translateY(-2px);

  box-shadow:
    0 4px 15px rgba(0, 0, 0, 0.12);

}


.quick-icon {

  width: 55px;

  height: 55px;

  min-width: 55px;

  border-radius: 12px;

  background:
    linear-gradient(
      135deg,
      #cc0000 0%,
      #8f0000 100%
    );

  color: white;

  display: flex;

  align-items: center;

  justify-content: center;

  font-size: 22px;

}


.quick-content {

  flex: 1;

}


.quick-content h3 {

  margin: 0 0 5px;

  font-size: 18px;

  font-weight: 700;

  color: #2d3748;

}


.quick-content p {

  margin: 0;

  color: #718096;

  font-size: 14px;

  line-height: 1.5;

}


.quick-button {

  display: inline-flex;

  align-items: center;

  justify-content: center;

  gap: 8px;

  padding: 10px 18px;

  background: #cc0000;

  color: white;

  text-decoration: none;

  border-radius: 8px;

  font-size: 14px;

  font-weight: 600;

  white-space: nowrap;

  transition:
    background 0.2s,
    transform 0.2s;

}


.quick-button:hover {

  background: #990000;

  color: white;

  transform: translateX(2px);

}


/* =========================================================
   CHARTS GRID
   ========================================================= */

.charts-grid {

  display: grid;

  grid-template-columns:
    1fr 1fr;

  gap: 20px;

  margin-bottom: 30px;

}


.chart-card {

  background: white;

  border-radius: 12px;

  padding: 20px;

  box-shadow:
    0 2px 10px rgba(0, 0, 0, 0.08);

}


.card-header {

  display: flex;

  justify-content: space-between;

  align-items: center;

  margin-bottom: 20px;

}


.card-header h3 {

  margin: 0;

  font-size: 18px;

  color: #2d3748;

}


.period-select {

  padding: 5px 10px;

  border:
    1px solid #e2e8f0;

  border-radius: 6px;

  background: white;

  color: #4a5568;

  font-size: 14px;

  cursor: pointer;

}


.btn-refresh {

  background: none;

  border: none;

  color: #718096;

  font-size: 16px;

  cursor: pointer;

  transition:
    transform 0.3s;

}


.btn-refresh:hover {

  transform:
    rotate(180deg);

  color: #2d3748;

}


/* =========================================================
   ACTIVITY SECTION
   ========================================================= */

.activity-section {

  background: white;

  border-radius: 12px;

  padding: 20px;

  box-shadow:
    0 2px 10px rgba(0, 0, 0, 0.08);

}


.section-header {

  display: flex;

  justify-content: space-between;

  align-items: center;

  margin-bottom: 20px;

}


.section-header h3 {

  margin: 0;

  font-size: 18px;

  color: #2d3748;

}


.btn-view-all {

  padding: 8px 16px;

  background: #667eea;

  color: white;

  border: none;

  border-radius: 6px;

  cursor: pointer;

  font-size: 14px;

  transition:
    background 0.2s;

}


.btn-view-all:hover {

  background: #5a67d8;

}


.table-container {

  overflow-x: auto;

}


.activity-table {

  width: 100%;

  border-collapse: collapse;

}


.activity-table th {

  text-align: left;

  padding: 12px;

  background: #f7fafc;

  color: #4a5568;

  font-weight: 600;

  font-size: 14px;

}


.activity-table td {

  padding: 12px;

  border-bottom:
    1px solid #edf2f7;

  color: #2d3748;

}


.activity-table tr:hover {

  background: #f7fafc;

}


.status-badge {

  padding: 4px 12px;

  border-radius: 20px;

  font-size: 12px;

  font-weight: 500;

  display: inline-block;

}


.status-review {

  background: #ebf8ff;

  color: #2b6cb0;

}


.status-approved {

  background: #f0fff4;

  color: #276749;

}


.status-pending {

  background: #fffaf0;

  color: #c05621;

}


.status-discussion {

  background: #fef2f2;

  color: #c53030;

}


.btn-action {

  background: none;

  border: none;

  color: #667eea;

  cursor: pointer;

  padding: 4px 8px;

  border-radius: 4px;

  transition:
    background 0.2s;

}


.btn-action:hover {

  background: #ebf4ff;

}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 968px) {

  .charts-grid {

    grid-template-columns: 1fr;

  }

}


@media (max-width: 768px) {

  .quick-card {

    flex-direction: column;

    align-items: flex-start;

  }


  .quick-button {

    width: 100%;

  }


  .dashboard-container {

    padding: 15px;

  }


  .stats-grid {

    grid-template-columns: 1fr 1fr;

  }


  .stat-card {

    padding: 15px;

  }


  .dashboard-header {

    padding: 15px;

  }


  .header-content {

    flex-direction: column;

    gap: 15px;

    align-items: flex-start;

  }


  .header-actions {

    width: 100%;

    justify-content: space-between;

  }

}


@media (max-width: 480px) {

  .stats-grid {

    grid-template-columns: 1fr;

  }


  .quick-access-grid {

    grid-template-columns: 1fr;

  }


  .stat-card {

    padding: 12px;

  }

}

</style>
