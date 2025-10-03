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

const isPasswordVisible = ref([false, false]);

const togglePasswordVisibility = (index) => {
  isPasswordVisible.value[index] = !isPasswordVisible.value[index];
};

const closeDialog = () => {
  setDialogVisible(false);
};

const isVisible = computed({
  get: () => props.isDialogVisible,
  set: (val) => setDialogVisible(val),
});

const form = reactive({ ...props.formData });

const isFormValid = computed(() => {
  return (
    form.name &&
    form.email &&
    (form.role === 'user' || form.role === 'admin') &&
    form.password &&
    form.password_confirmation &&
    form.password === form.password_confirmation
  );
});

const onSubmit = () => {
  emit('submit', form);
};
</script>

<template>
  <VDialog
    v-model="isVisible"
    max-width="600"
    @click:outside="closeDialog()"
  >
    <DialogCloseBtn @click="closeDialog()" />
    <VCard title="User">
      <VCardText>
        <VRow>
          <VCol cols="12">
            <VTextField
              v-model="form.name"
              label="Name"
            />
            <div
              class="mt-2 text-error"
              v-if="errors.name"
            >
              {{ errors.name[0] }}
            </div>
          </VCol>

          <VCol cols="12">
            <VTextField
              v-model="form.email"
              label="Email"
            />
            <div
              class="mt-2 text-error"
              v-if="errors.email"
            >
              {{ errors.email[0] }}
            </div>
          </VCol>

          <VCol cols="12">
            <VTextField
              v-model="form.password"
              label="Password"
              :type="isPasswordVisible[0] ? 'text' : 'password'"
              :append-inner-icon="
                isPasswordVisible[0] ? 'tabler-eye-off' : 'tabler-eye'
              "
              @click:append-inner="togglePasswordVisibility(0)"
            />
            <div
              class="mt-2 text-error"
              v-if="errors.password"
            >
              {{ errors.password[0] }}
            </div>
          </VCol>

          <VCol cols="12">
            <VTextField
              v-model="form.password_confirmation"
              label="Confirm Password"
              :type="isPasswordVisible[1] ? 'text' : 'password'"
              :append-inner-icon="
                isPasswordVisible[1] ? 'tabler-eye-off' : 'tabler-eye'
              "
              @click:append-inner="togglePasswordVisibility(1)"
            />
            <div
              class="mt-2 text-error"
              v-if="errors.password_confirmation"
            >
              {{ errors.password_confirmation[0] }}
            </div>
          </VCol>

          <VCol cols="12">
            <AppSelect
              v-model="form.role"
              :hint="form.role"
              label="Role"
              :items="roleOptions"
              item-title="name"
              item-value="id"
              persistent-hint
              return-object
              single-line
              placeholder="Select Role"
            />
            <div
              class="mt-2 text-error"
              v-if="errors.role_id"
            >
              {{ errors.role_id[0] }}
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
          color="primary"
          :disabled="!isFormValid"
          :loading="dialogSubmitLoading"
          @click="onSubmit"
        >
          Add
        </VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>
