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

const nameRules = [
  (value) => value.length <= 20 || 'Name must be less than 20 characters.',
  (value) => !!value || 'Name is required.',
];

const isVisible = computed({
  get: () => props.isDialogVisible,
  set: (val) => setDialogVisible(val),
});

const form = reactive({ ...props.formData });

const isFormValid = computed(() => {
  return form.ledger_id && form.type && form.name ? true : false;
});

const formatName = () => {
  if (!form.name) return;
  form.name =
    form.name.charAt(0).toUpperCase() + form.name.slice(1).toLowerCase();
};

const onSubmit = () => {
  let payload = {
    ...form,
    ledger_id: form.ledger_id.id,
    type: form.type.value,
  };

  emit('submit', payload, props.isEdit);
};
</script>

<template>
  <VDialog
    v-model="isVisible"
    max-width="600"
    @click:outside="closeDialog()"
  >
    <DialogCloseBtn @click="closeDialog()" />

    <VCard title="Ledger Category">
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
            <div
              class="mt-2 text-error"
              v-if="errors.ledger_id"
            >
              {{ errors.ledger_id[0] }}
            </div>
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
            <div
              class="mt-2 text-error"
              v-if="errors.type"
            >
              {{ errors.type[0] }}
            </div>
          </VCol>

          <VCol cols="12">
            <VTextField
              v-model="form.name"
              label="Name"
              placeholder="Name"
              :rules="nameRules"
              @input="formatName"
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
        >
          Close
        </VBtn>
        <VBtn
          variant="tonal"
          :disabled="!isFormValid"
          :loading="props.dialogSubmitLoading"
          @click="onSubmit"
          >Save</VBtn
        >
      </VCardActions>
    </VCard>
  </VDialog>
</template>
