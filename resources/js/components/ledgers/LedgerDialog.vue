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

const setDialogVisible = (val) => {
  emit('update:isDialogVisible', val);
};

const closeEditDialog = () => {
  emit('closeEditDialog');
};

const closeDialog = () => {
  props.isEdit ? closeEditDialog() : setDialogVisible(false);
};

const isVisible = computed({
  get: () => props.isDialogVisible,
  set: (val) => setDialogVisible(val),
});

const isSubmitLoading = computed(() => props.dialogSubmitLoading);

const form = reactive({ ...props.formData });

const nameRules = [
  (value) => value.length <= 20 || 'Name must be less than 20 characters.',
  (value) => !!value || 'Name is required.',
];

const formatName = () => {
  if (!form.name) return;
  form.name = form.name.toUpperCase();
};

const onSubmit = () => {
  emit('submit', form, props.isEdit);
};
</script>

<template>
  <VDialog
    v-model="isVisible"
    max-width="600"
    @click:outside="closeDialog()"
  >
    <DialogCloseBtn @click="closeDialog()" />
    <VCard title="Ledger">
      <VCardText>
        <VRow>
          <VCol
            cols="12"
            sm="12"
            md="12"
          >
            <VTextField
              v-model="form.name"
              label="Ledger Name"
              :rules="nameRules"
              @input="formatName"
              required
            />
            <div
              class="mt-2 text-error"
              v-if="errors.name"
            >
              {{ errors.name[0] }}
            </div>
          </VCol>
        </VRow>
      </VCardText>
      <VCardActions>
        <VBtn
          variant="tonal"
          color="secondary"
          @click="closeDialog()"
          :disabled="isSubmitLoading"
        >
          Close
        </VBtn>
        <VBtn
          variant="tonal"
          :disabled="!form.name"
          :loading="isSubmitLoading"
          @click="onSubmit"
        >
          {{ isEdit ? 'Update' : 'Create' }}
        </VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>
