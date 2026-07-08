<template>
  <div class="principal">
    <div class="comision-header">
      <h2 class="comision-titulo">
        Comisiones del Concejo Municipal de Potosí
      </h2>
        <p class="comision-subtitulo">Las 10 comisiones que trabajan por el desarrollo de Potosí</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-4" v-for="comision in comisiones" :key="comision.id">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-body text-center">
            <img :src="comision.logo"
                :alt="comision.nombre"
                class="img-fluid h-1"
                style="display: block; margin: 0 auto; object-fit: cover; min-height: 60px;">
            <h5 class="card-title mt-3 fw-bold">{{ comision.nombre }}</h5>
            <p class="card-text text-muted" style="text-align: justify;">{{ comision.descripcion }}</p>
            <span class="responsable-trigger">
              {{ comision.responsable }}
              <div class="tooltip-foto">
                <img :src="comision.foto_responsable"
                    :alt="comision.responsable"
                    style="width:120px; height:120px; object-fit:cover; border-radius:50%;">
                <br><small>{{ comision.responsable }}</small>
              </div>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
name: 'Comisiones',
    data() {
        return {
            comisiones: [
                {
                    id: 1,
                    nombre: 'Comisión Jurídica y Desarrollo Institucional',
                    descripcion: 'Tiene como objetivo velar por la legalidad, constitucionalidad y transparencia de los actos del Gobierno Municipal, promoviendo además el fortalecimiento institucional a través de la fiscalización normativa, administrativa y organizacional. Su accionar se orienta a garantizar el cumplimiento de la normativa vigente y mejorar la gestión municipal mediante políticas, estrategias y planes institucionales.',
                    logo: '/images/iconos/juridica.png',
                    responsable: 'Ing. Guido Armando Cruz Mora',
                    foto_responsable: '/images/concejales/Guido_Cruz.png',
                },
                {
                    id: 2,
                    nombre: 'Comisión de Educación y Cultura',
                    descripcion: 'Fomenta la educación, cultura y patrimonio histórico de Potosí.',
                    logo: '/images/comisiones/comision2.jpg',
                    integrantes: 4
                },
                {
                    id: 3,
                    nombre: 'Comisión de Salud y Medio Ambiente',
                    descripcion: 'Promueve políticas de salud pública y protección del medio ambiente.',
                    logo: '/images/comisiones/comision3.jpg',
                    integrantes: 4
                },
                {
                    id: 4,
                    nombre: 'Comisión de Desarrollo Económico',
                    descripcion: 'Impulsa el desarrollo económico y el empleo en la ciudad.',
                    logo: '/images/comisiones/comision4.jpg',
                    integrantes: 4
                },
                {
                    id: 5,
                    nombre: 'Comisión de Infraestructura',
                    descripcion: 'Supervisa proyectos de infraestructura y obras públicas.',
                    logo: '/images/comisiones/comision5.jpg',
                    integrantes: 5
                },
                {
                    id: 6,
                    nombre: 'Comisión de Derechos Humanos',
                    descripcion: 'Vela por el respeto de los derechos humanos en la ciudad.',
                    logo: '/images/comisiones/comision6.jpg',
                    integrantes: 3
                },
                {
                    id: 7,
                    nombre: 'Comisión de Juventud y Deportes',
                    descripcion: 'Promueve la participación juvenil y el desarrollo deportivo.',
                    logo: '/images/comisiones/comision7.jpg',
                    integrantes: 3
                },
                {
                    id: 8,
                    nombre: 'Comisión de Transparencia',
                    descripcion: 'Garantiza la transparencia y rendición de cuentas.',
                    logo: '/images/comisiones/comision8.jpg',
                    integrantes: 3
                },
                {
                    id: 9,
                    nombre: 'Comisión de Desarrollo Social',
                    descripcion: 'Atiende las necesidades de los grupos más vulnerables.',
                    logo: '/images/comisiones/comision9.jpg',
                    integrantes: 4
                },
                {
                    id: 10,
                    nombre: 'Comisión de Relaciones Internacionales',
                    descripcion: 'Fomenta hermanamientos y cooperación internacional.',
                    logo: '/images/comisiones/comision10.jpg',
                    integrantes: 3
                }
            ]
        };
    }
};
</script>

<style scoped>
.principal {
  margin: 0 auto;
  padding: 1rem;
  background-image: url('/images/fondo.png');
  background-size: cover;
  background-position: center;
}
.comision-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 1rem;
  color: red;
}
.comision-titulo {
  font-size: 2.5rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  letter-spacing: 1px;
}
.comision-subtitulo {
  font-size: 1.2rem;
  margin: 0;
  color: #555;
}
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 15px;
    text-align: justify;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
}

.card i {
    transition: transform 0.3s ease;
}

.card:hover i {
    transform: scale(1.1);
}
.responsable-trigger {
  color: red;
  cursor: pointer;
  position: relative;
  display: inline-block;
  font-weight: 500;
  text-decoration: underline; /* Opcional, para indicar que es interactivo */
  text-underline-offset: 2px; /* Opcional */
}

/* Tooltip oculto por defecto */
.tooltip-foto {
  visibility: hidden;
  opacity: 0;
  width: 150px;
  background-color: #fff;
  color: #333;
  text-align: center;
  border-radius: 10px;
  padding: 12px;
  position: absolute;
  z-index: 1000;
  bottom: 130%; /* Aparece arriba */
  left: 50%;
  transform: translateX(-50%);
  transition: opacity 0.3s ease, visibility 0.3s ease;
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
  border: 1px solid #ddd;
  font-size: 13px;
  font-weight: normal;
}

/* Flecha del tooltip */
.tooltip-foto::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -6px;
  border-width: 6px;
  border-style: solid;
  border-color: #fff transparent transparent transparent;
}

/* Mostrar al hacer hover */
.responsable-trigger:hover .tooltip-foto {
  visibility: visible;
  opacity: 1;
}
</style>
