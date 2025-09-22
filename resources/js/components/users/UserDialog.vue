<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  roleOptions: {
    type: Array,
    required: true,
  },
  formData: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: true,
  },
  dialogSubmitLoading: {
    type: Boolean,
    required: true,
    default: false,
  },

  isEdit: {
    type: Boolean,
    required: false,
    default: false,
  },
});

const emit = defineEmits([
  'update:isDialogVisible',
  'submit',
  'closeEditDialog',
]);

const setDialogVisible = (val) => {
  emit('update:isDialogVisible', val);
};

const closeDialog = () => {
  setDialogVisible(false);
};

const isVisible = computed({
  get: () => props.isDialogVisible,
  set: (val) => setDialogVisible(val),
});
</script>

<template>
  <VDialog
    v-model="isVisible"
    max-width="600"
    @click:outside="closeDialog()"
  >
    <DialogCloseBtn @click="closeDialog()" />
    <VCard></VCard>
  </VDialog>
</template>
