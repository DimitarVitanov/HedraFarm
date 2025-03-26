<script setup>
import { defineProps, defineEmits } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css'; // Quill theme

const props = defineProps({
  modelValue: String, // Enable v-model
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
  <QuillEditor
    :content="modelValue"
    content-type="html"
    theme="snow"
    @update:content="emit('update:modelValue', $event)"
    :toolbar="[
      ['bold', 'italic', 'underline', 'strike'], // Text styling
      [{ header: 1 }, { header: 2 }], // Headers
      [{ list: 'ordered' }, { list: 'bullet' }], // Lists
      [{ align: [] }],
      ['link', 'image'],
      ['clean']
    ]"
  />
</template>

<style>
.ql-editor {
  min-height: 200px;
}
</style>
