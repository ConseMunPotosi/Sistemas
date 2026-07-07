<template>
  <div class="image-overlay-container">
    <div 
      class="image-stack"
      :style="containerStyle"
    >
      <img
        v-for="(image, index) in images"
        :key="index"
        :src="image.src"
        :alt="image.alt || `Imagen ${index + 1}`"
        class="overlay-image"
        :style="getImageStyle(index)"
        @load="onImageLoad"
        ref="imagesRef"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'ImageOverlay',
  props: {
    images: {
      type: Array,
      required: true,
      validator: (value) => {
        return value.every(item => item.src && typeof item.src === 'string');
      }
    },
    // Opciones de posicionamiento
    position: {
      type: String,
      default: 'center', // 'center', 'top-left', 'top-right', 'bottom-left', 'bottom-right'
      validator: (value) => {
        return ['center', 'top-left', 'top-right', 'bottom-left', 'bottom-right'].includes(value);
      }
    },
    // Espaciado entre imágenes (en píxeles)
    spacing: {
      type: Number,
      default: 0
    },
    // Opacidad de las imágenes (0-1)
    opacity: {
      type: Number,
      default: 1
    },
    // Índice de la imagen que estará al frente
    frontIndex: {
      type: Number,
      default: 0
    },
    // Si se deben mezclar las imágenes con blend mode
    blendMode: {
      type: String,
      default: 'normal'
    }
  },
  data() {
    return {
      imageSizes: [],
      maxWidth: 0,
      maxHeight: 0,
      imagesLoaded: false
    };
  },
  computed: {
    containerStyle() {
      return {
        position: 'relative',
        display: 'inline-block',
        width: this.imagesLoaded ? `${this.maxWidth}px` : 'auto',
        height: this.imagesLoaded ? `${this.maxHeight}px` : 'auto',
        minWidth: '100px',
        minHeight: '100px'
      };
    }
  },
  methods: {
    getImageStyle(index) {
      const baseStyle = {
        position: 'absolute',
        top: 0,
        left: 0,
        opacity: this.opacity,
        mixBlendMode: this.blendMode
      };

      // Si la imagen tiene un tamaño específico (cuando todas están cargadas)
      if (this.imageSizes[index]) {
        const { width, height } = this.imageSizes[index];
        baseStyle.width = `${width}px`;
        baseStyle.height = `${height}px`;
        baseStyle.objectFit = 'none'; // Mantener tamaño original
      }

      // Aplicar posicionamiento
      const positionStyle = this.getPositionStyle(index);
      return { ...baseStyle, ...positionStyle };
    },

    getPositionStyle(index) {
      const spacing = this.spacing;
      const offset = index * spacing;
      
      switch (this.position) {
        case 'top-left':
          return { top: `${offset}px`, left: `${offset}px` };
        case 'top-right':
          return { top: `${offset}px`, right: `${offset}px`, left: 'auto' };
        case 'bottom-left':
          return { bottom: `${offset}px`, left: `${offset}px`, top: 'auto' };
        case 'bottom-right':
          return { bottom: `${offset}px`, right: `${offset}px`, top: 'auto', left: 'auto' };
        case 'center':
        default:
          return {
            top: '50%',
            left: '50%',
            transform: `translate(-50%, -50%) translate(${offset}px, ${offset}px)`
          };
      }
    },

    onImageLoad(event) {
      const img = event.target;
      const index = this.$refs.imagesRef.indexOf(img);
      
      // Guardar dimensiones originales
      this.imageSizes[index] = {
        width: img.naturalWidth,
        height: img.naturalHeight
      };

      // Actualizar dimensiones máximas
      this.maxWidth = Math.max(this.maxWidth, img.naturalWidth);
      this.maxHeight = Math.max(this.maxHeight, img.naturalHeight);

      // Verificar si todas las imágenes están cargadas
      if (this.imageSizes.length === this.images.length) {
        this.imagesLoaded = true;
      }
    },

    // Método para cambiar el orden de las imágenes
    setFrontImage(index) {
      this.$emit('update:frontIndex', index);
    }
  },
  watch: {
    frontIndex(newIndex) {
      // Reordenar las imágenes según el índice frontal
      if (this.images.length > 1) {
        const imagesCopy = [...this.images];
        const frontImage = imagesCopy.splice(newIndex, 1)[0];
        imagesCopy.push(frontImage);
        // Nota: Para una implementación completa, necesitarías manejar el reordenamiento
        this.$emit('update:images', imagesCopy);
      }
    }
  }
};
</script>

<style scoped>
.image-overlay-container {
  display: inline-block;
  margin: 10px;
}

.image-stack {
  position: relative;
  display: inline-block;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  overflow: hidden;
  background: #f5f5f5;
}

.overlay-image {
  position: absolute;
  top: 0;
  left: 0;
  pointer-events: none;
  user-select: none;
  display: block;
  image-rendering: auto;
}

/* Para imágenes que aún no se han cargado */
.overlay-image:not([loaded]) {
  opacity: 0;
}

.overlay-image[loaded] {
  opacity: 1;
  transition: opacity 0.3s ease;
}

/* Estilos para imágenes con hover */
.overlay-image:hover {
  filter: brightness(1.1);
  transition: filter 0.2s ease;
}

/* Añadir sombra a las imágenes para mejor visibilidad */
.overlay-image:not(:first-child) {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Loading placeholder */
.image-stack::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 40px;
  height: 40px;
  border: 3px solid #f3f3f3;
  border-top: 3px solid #3498db;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  display: none;
}

.image-stack:not(.loaded)::before {
  display: block;
}

@keyframes spin {
  0% { transform: translate(-50%, -50%) rotate(0deg); }
  100% { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>