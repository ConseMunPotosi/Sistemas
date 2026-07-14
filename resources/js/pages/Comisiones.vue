<template>
  <div class="principal">
    <div class="comision-header">
      <h2 class="comision-titulo">Comisiones del Concejo Municipal de Potosí</h2>
        <p class="comision-subtitulo">Las 10 comisiones que trabajan por el desarrollo integral y sostenible de Potosí, abordando las diversas áreas estratégicas que demanda el crecimiento económico, social y cultural de nuestro municipio.</p>
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
            <b><p>Presidente de Comisión</p></b>
            <span class="responsable-trigger">
              {{ comision.responsable }}
              <div class="tooltip-foto">
                <img :src="comision.foto_responsable"
                    :alt="comision.responsable"
                    style="width:120px; height:120px; object-fit:cover; border-radius:50%;">
                <br><small>{{ comision.responsable }}
                    <p><b>Presidente de la Comisión</b></p>
                </small>
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
                    descripcion: 'Vela por la legalidad, constitucionalidad y transparencia de los actos del Gobierno Municipal, promoviendo además el fortalecimiento institucional a través de la fiscalización normativa, administrativa y organizacional. Su accionar se orienta a garantizar el cumplimiento de la normativa vigente y mejorar la gestión municipal mediante políticas, estrategias y planes institucionales.',
                    logo: '/images/iconos/juridica.png',
                    responsable: 'Cjal. Guido Armando Cruz Mora',
                    foto_responsable: '/images/concejales/Guido_Cruz.png',
                },
                {
                    id: 2,
                    nombre: 'Comisión Económica, Financiera',
                    descripcion: 'Analiza, evalúa y fiscaliza la gestión financiera, tributaria y presupuestaria del Gobierno Municipal, asegurando el cumplimiento de la normativa vigente, la eficiencia en el uso de los recursos públicos y la transparencia en la administración económico-financiera municipal',
                    logo: '/images/iconos/economica.png',
                    responsable: 'Cjal. A designar',
                    foto_responsable: '/images/concejales/A_designar.png',
                },
                {
                    id: 3,
                    nombre: 'Comisión de Desarrollo Territorial y Límites',
                    descripcion: 'Realiza el seguimiento, evaluación y fiscalización de la planificación territorial del Municipio, promoviendo la adecuada delimitación urbano-rural, el cumplimiento de normas urbanísticas y catastrales, así como la resolución de conflictos de límites internos y externos, en el marco de las competencias municipales y la normativa vigente. ',
                    logo: '/images/iconos/limites.png',
                    responsable: 'Cjal. Maria del Carmen Michel Araujo',
                    foto_responsable: '/images/concejales/Carmen_Michel.png',
                },
                {
                    id: 4,
                    nombre: 'Comisión Técnica',
                    descripcion: 'Analiza, fiscaliza y coordina el cumplimiento técnico de los proyectos de inversión municipal, asegurando su adecuación a normas urbanísticas, catastrales y de planificación territorial. Además, supervisa la ejecución técnica de obras y proyectos, promueve el desarrollo sostenible, y representa al Concejo en instancias de formulación de políticas y planificación técnica regional.',
                    logo: '/images/iconos/tecnica.png',
                    responsable: 'Cjal. Manuel Alejandro Calizaya Limachi',
                    foto_responsable: '/images/concejales/Manuel_Calizaya.png',
                },
                {
                    id: 5,
                    nombre: 'Comisión de Desarrollo Humano',
                    descripcion: 'Analiza, fiscaliza y promueve la correcta gestión de los servicios de salud, educación, deportes y recreación en el municipio. Su labor se orienta a garantizar el mantenimiento adecuado de la infraestructura, la asignación eficiente de recursos y la supervisión de los programas sociales que contribuyan al bienestar de la comunidad.',
                    logo: '/images/iconos/humano.png',
                    responsable: 'Cjal. Eddy Fernandez Fuertes',
                    foto_responsable: '/images/concejales/Eddy_Fernandez.png',
                },
                {
                    id: 6,
                    nombre: 'Comisión de Género Generacional',
                    descripcion: 'Analiza, fiscaliza y promueve la protección de los derechos y el desarrollo integral de grupos prioritarios como mujeres, niñas, niños, adolescentes, personas adultas mayores, personas con discapacidad, familias y pueblos indígena originario campesinos. Su accionar está orientado a garantizar la equidad, la inclusión y la calidad en los servicios que el Gobierno Municipal brinda a estos sectores, mediante la supervisión de programas, servicios y normativas específicas.',
                    logo: '/images/iconos/genero.png',
                    responsable: 'Cjal. Christie Mónica Chacón Duran',
                    foto_responsable: '/images/concejales/Monica_Chacon.png',
                },
                {
                    id: 7,
                    nombre: 'DesarrolloEconómico, Productivo y Agropecuario',
                    descripcion: 'Promueve, coordina y fiscaliza políticas, programas y proyectos que fomenten el desarrollo económico local, productivo y agropecuario en áreas urbanas y rurales. Su enfoque está orientado a la inclusión de estos sectores dentro de la planificación y presupuesto municipal, contribuyendo al desarrollo sostenible y equitativo del Municipio, alineado con las normativas nacionales y municipales.',
                    logo: '/images/iconos/productivo.png',
                    responsable: 'Cjal. Ariel Jiménez Gómez',
                    foto_responsable: '/images/concejales/Ariel_Jimenez.png',
                },
                {
                    id: 8,
                    nombre: 'Comisión de Turnismo, Cultura y Preservación de Áreas Históricas',
                    descripcion: 'Fomentar la protección, preservación, promoción y difusión del patrimonio histórico, cultural y turístico del Municipio, garantizando el desarrollo sostenible de las actividades culturales y turísticas, respetando las normativas municipales, nacionales e internacionales en la materia.',
                    logo: '/images/iconos/turismo.png',
                    responsable: 'Cjal. Jacqueline Lourdes Gutiérrez Carrasco',
                    foto_responsable: '/images/concejales/Jacqueline_Gutierrez.png',
                },
                {
                    id: 9,
                    nombre: 'Comisión de Medio Ambiente, Minería y Forestación',
                    descripcion: 'Fiscaliza, promueve y recomienda políticas, normas y acciones en las áreas de medio ambiente, minería y recursos forestales, en el marco de la legislación vigente y el desarrollo sostenible del Municipio. Su accionar busca preservar los recursos naturales, garantizar el uso racional del territorio, y promover una gestión pública ambientalmente responsable.',
                    logo: '/images/iconos/mineria.png',
                    responsable: 'Cjal. Clementina Aroni Mamani',
                    foto_responsable: '/images/concejales/Clementina_Aroni.png',
                },
                {
                    id: 10,
                    nombre: 'Comisión de Servicios Públicos',
                    descripcion: 'Brindar asesoramiento técnico especializado en la formulación, fiscalización y seguimiento de políticas, normativas y proyectos orientados a garantizar el acceso equitativo y eficiente a los servicios públicos en la jurisdicción municipal, en cumplimiento del marco normativo vigente.',
                    logo: '/images/iconos/publicos.png',
                    responsable: 'Cjal. Claudio German Clemente Vedia',
                    foto_responsable: '/images/concejales/Claudio_Clemente.png',
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
  color: #cc0000;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
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
