<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  ledgerOptions: {
    type: Array,
    required: true,
  },
  typeOptions: {
    type: Array,
    required: true,
  },

  formData: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['update:isDialogVisible', 'submit']);

const dialogModelValueUpdate = (val) => {
  emit('update:isDialogVisible', val);
};

const isVisible = computed({
  get: () => props.isDialogVisible,
  set: (val) => dialogModelValueUpdate(val),
});

const form = reactive({ ...props.formData });
const isLoading = ref(false);

const isFormValid = computed(() => {
  return form.ledger_id && form.type && form.name ? true : false;
});

const onSubmit = () => {
  isLoading.value = true;
  let payload = {
    ...form,
    ledger_id: form.ledger_id.id,
    type: form.type.value,
  };

  emit('submit', payload);
};
</script>

<template>
  <VDialog
    v-model="isVisible"
    max-width="600"
  >
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <VCard title="Add Category">
      <VCardText>
        <VRow>
          <VCol cols="12">
            <AppSelect
              v-model="form.ledger_id"
              :hint="form.ledger_id?.name"
              label="Ledger"
              :items="ledgerOptions"
              item-title="name"
              item-value="id"
              persistent-hint
              return-object
              single-line
              placeholder="Select Ledger"
            />
          </VCol>

          <VCol cols="12">
            <AppSelect
              v-model="form.type"
              :hint="form.type?.label"
              label="Type"
              :items="typeOptions"
              item-title="label"
              item-value="value"
              persistent-hint
              return-object
              single-line
              placeholder="Select Type"
            />
          </VCol>

          <VCol cols="12">
            <VTextField
              v-model="form.name"
              label="Name"
            />
          </VCol>
        </VRow>
      </VCardText>

      <VCardActions>
        <VBtn
          variant="tonal"
          color="secondary"
          @click="dialogModelValueUpdate(false)"
        >
          Close
        </VBtn>
        <VBtn
          variant="tonal"
          :disabled="!isFormValid"
          :loading="isLoading"
          @click="onSubmit"
          >Save</VBtn
        >
      </VCardActions>
    </VCard>
  </VDialog>
</template>
