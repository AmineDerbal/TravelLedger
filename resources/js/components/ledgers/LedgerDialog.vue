<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
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

const SetDialogVisible = (val) => {
  emit('update:isDialogVisible', val);
};

const closeEditDialog = () => {
  emit('closeEditDialog');
};

const closeDialog = () => {
  props.isEdit ? closeEditDialog() : SetDialogVisible(false);
};
</script>

<template>
  <VDialog
    v-model="isDialogVisible"
    max-width="600"
    @click:outside="closeDialog()"
  >
    <DialogCloseBtn @click="closeDialog()" />
  </VDialog>
</template>
