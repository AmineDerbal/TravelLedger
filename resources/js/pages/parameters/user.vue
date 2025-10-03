<script setup>
import useUserStore from '@/store/userStore';
import { useAbility } from '@/plugins/casl/composables/useAbility';

definePage({
  meta: {
    requiresAuth: true,
    action: 'manage',
    subject: 'User',
  },
});

const userStore = useUserStore();
const ability = useAbility();

const users = computed(() => userStore.users);
const userRoleOptions = computed(() => userStore.roles);
const errors = computed(() => userStore.errors);

const defaultFormat = {
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: null,
};

const isDialogVisible = ref(false);
const dialogSubmitLoading = ref(false);
const dialogKey = ref(0);
const formData = ref({ ...defaultFormat });

const headers = [
  { title: 'Name', key: 'name' },
  { title: 'Email', key: 'email' },
  { title: 'Role', key: 'role' },
  { title: 'Is Active', key: 'is_active' },
  { title: 'Actions', key: 'actions' },
];

const increaseDialogKey = () => {
  dialogKey.value += 1;
};

const resetDialog = () => {
  isDialogVisible.value = false;
  formData.value = { ...defaultFormat };
  increaseDialogKey();
};

const handleSubmit = async (data) => {
  dialogSubmitLoading.value = true;
  const response = await userStore.register(data);
  const expectedStatus = 201;
  dialogSubmitLoading.value = false;
  if (response.status === expectedStatus) {
    await userStore.getUsers();
    resetDialog();
  }
};

onBeforeMount(async () => {
  await userStore.getUsers();
  await userStore.getUserRoles();
});
</script>

<template>
  <UserDialog
    v-model:isDialogVisible="isDialogVisible"
    :roleOptions="userRoleOptions"
    :formData="formData"
    :errors="errors"
    :dialogSubmitLoading="dialogSubmitLoading"
    @submit="handleSubmit"
    :key="dialogKey"
  />
  <VCard title="Users">
    <VCardText>
      <VRow class="align-end">
        <VCol
          cols="12"
          md="4"
        >
          <VBtn
            v-if="ability.can('manage', 'User')"
            variant="tonal"
            color="primary"
            @click="isDialogVisible = true"
          >
            Add User
          </VBtn>
        </VCol>
      </VRow>
    </VCardText>
    <VDivider />
    <UserTable
      :users="users"
      :headers="headers"
    />
  </VCard>
</template>
