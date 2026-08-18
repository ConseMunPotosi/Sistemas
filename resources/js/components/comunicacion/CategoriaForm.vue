<template>
  <div v-if="show" class="modal-overlay" @click.self="closeModal">
    <div class="modal-content">
      <h3>{{ isEditing ? 'Editar Categoría' : 'Nueva Categoría' }}</h3>
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label>Nombre</label>
          <input v-model="form.nombre" type="text" required />
        </div>
        <div class="form-group">
          <label>Descripción</label>
          <textarea v-model="form.descripcion" rows="3"></textarea>
        </div>
        <div class="form-group" v-if="isEditing">
          <label>Estado</label>
          <select v-model="form.estado">
            <option :value="true">Activo</option>
            <option :value="false">Inactivo</option>
          </select>
        </div>
        <div class="modal-actions">
          <button type="button" @click="closeModal">Cancelar</button>
          <button type="submit" class="btn-save">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import { useNoticiasStore } from '../../stores/noticias';

const props = defineProps({ show: Boolean, categoria: Object });
const emit = defineEmits(['update:show', 'saved']);
const store = useNoticiasStore();

const isEditing = ref(false);
const form = reactive({ nombre: '', descripcion: '', estado: true });

watch(() => props.show, (val) => {
  if (val) {
    if (props.categoria) {
      isEditing.value = true;
      Object.assign(form, props.categoria);
    } else {
      isEditing.value = false;
      form.nombre = ''; form.descripcion = ''; form.estado = true;
    }
  }
});

const closeModal = () => emit('update:show', false);

const handleSubmit = async () => {
  let res;
  if (isEditing.value) res = await store.updateCategoria(props.categoria.id_categoria, form);
  else res = await store.createCategoria(form);

  if (res.success) { closeModal(); emit('saved'); }
};
</script>

<style scoped>
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 9999; }
.modal-content { background: white; padding: 24px; border-radius: 12px; width: 100%; max-width: 500px; }
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
.modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
.btn-save { background: #cc0000; color: white; border: none; padding: 8px 20px; border-radius: 6px; cursor: pointer; }
</style>
