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

const form = reactive({ ...props.formData });
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
              type="password"
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
              type="password"
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
    </VCard>
  </VDialog>
</template>
