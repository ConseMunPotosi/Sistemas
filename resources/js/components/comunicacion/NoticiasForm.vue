<template>
  <div v-if="show" class="modal-overlay" @click.self="closeModal">
    <div class="modal-content large-modal">
      <h3>{{ isEditing ? 'Editar Noticia' : 'Nueva Noticia' }}</h3>
      <form @submit.prevent="handleSubmit">
        <div class="grid-2">
          <div class="form-group">
            <label>Título</label>
            <input v-model="form.titulo" type="text" required />
          </div>
          <div class="form-group">
            <label>Categoría</label>
            <select v-model="form.id_categoria" required>
              <option
                v-if="isEditing && props.noticia?.id_categoria && !store.categorias.some(c => c.id_categoria === props.noticia.id_categoria)"
                :value="props.noticia.id_categoria"
              >
                {{ getCategoriaNombre(props.noticia.id_categoria) }} (Inactiva)
              </option>
              <option value="">Seleccionar...</option>
              <template v-if="store.categorias && Array.isArray(store.categorias)">
                <!-- Filtramos solo las activas directamente en el v-for -->
                <option
                  v-for="cat in store.categorias.filter(c => c.estado === true)"
                  :key="cat.id_categoria"
                  :value="cat.id_categoria"
                >
                  {{ cat.nombre }}
                </option>
              </template>
              <option v-else disabled>
                {{ store.loading ? 'Cargando categorías...' : 'No hay categorías activas disponibles' }}
              </option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Resumen</label>
          <textarea v-model="form.resumen" rows="2"></textarea>
        </div>
        <div class="form-group">
          <label>Contenido</label>
          <textarea v-model="form.contenido" rows="5"></textarea>
        </div>
        <h4>Opciones de Publicación</h4>
        <div class="grid-2">
          <div class="form-group">
            <label>Estado Publicación</label>
            <select v-model="form.estado_publicacion">
              <option value="borrador">Borrador</option>
              <option value="programado">Programado</option>
              <option value="publicado">Publicado</option>
            </select>
          </div>
          <div class="form-group">
            <label>Fecha de Publicación</label>
            <input v-model="form.fecha_publicacion" type="datetime-local" />
          </div>
        </div>

        <h4>Redes Sociales</h4>
        <div class="grid-2">
          <div class="form-group checkbox-group">
            <label><input v-model="form.publicado_web" type="checkbox" /> Publicar en Web</label>
          </div>
          <div class="form-group checkbox-group">
            <label><input v-model="form.publicado_facebook" type="checkbox" /> Publicar en Facebook</label>
          </div>
        </div>
        <div class="form-group" v-if="form.publicado_facebook">
          <label>Enlace a Facebook</label>
          <input v-model="form.enlace_facebook" type="url" />
        </div>

        <div class="modal-actions">
          <button type="button" @click="closeModal">Cancelar</button>
          <button type="submit" class="btn-save"> Noticia</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted } from 'vue';
import { useNoticiasStore } from '../../stores/noticias';

const props = defineProps({ show: Boolean, noticia: Object });
const emit = defineEmits(['update:show', 'saved']);
const store = useNoticiasStore();

const isEditing = ref(false);
const form = reactive({
  titulo: '', resumen: '', contenido: '', imagen_portada: '', id_categoria: '',
  estado_publicacion: 'borrador', fecha_publicacion: '',
  publicado_web: true, publicado_facebook: false, enlace_facebook: ''
});

watch(() => props.show, (val) => {
  if (val) {
    // Cargar categorías antes de abrir
    if (store.categorias.length === 0) store.fetchCategorias();

    if (props.noticia) {
      isEditing.value = true;
      Object.assign(form, props.noticia);
    } else {
      isEditing.value = false;
      Object.assign(form, { titulo: '', resumen: '', contenido: '', imagen_portada: '', id_categoria: '', estado_publicacion: 'borrador', fecha_publicacion: '', publicado_web: true, publicado_facebook: false, enlace_facebook: '' });
    }
  }
});

const closeModal = () => emit('update:show', false);

const handleSubmit = async () => {
  let res;
  if (isEditing.value) res = await store.updateNoticia(props.noticia.id_noticia, form);
  else res = await store.createNoticia(form);

  if (res.success) { closeModal(); emit('saved'); }
};
const getCategoriaNombre = (id) => {
  if (!id) return 'Sin categoría';
  const cat = store.categorias.find(c => c.id_categoria === id);
  return cat ? cat.nombre : 'Categoría no encontrada';
};
</script>

<style scoped>
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 9999; }
.large-modal { background: white; padding: 24px; border-radius: 12px; width: 100%; max-width: 800px; max-height: 90vh; overflow-y: auto; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; font-weight: 500; margin-bottom: 5px; }
.form-group input,
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  outline: none;
  transition: border-color 0.2s ease;
}
.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  border-color: #cc0000;
  box-shadow: 0 0 0 4px rgba(204, 0, 0, 0.1);
}
.checkbox-group label { display: flex; align-items: center; gap: 10px; font-weight: normal; }
.checkbox-group input { width: auto; }
.modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
.btn-save { background: #cc0000; color: white; border: none; padding: 8px 20px; border-radius: 6px; cursor: pointer; }
</style>
