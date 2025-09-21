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

const isDialogVisible = ref(false);
const defaultFormat = {
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: null,
};

const formData = ref({ ...defaultFormat });

const headers = [
  { title: 'Name', key: 'name' },
  { title: 'Email', key: 'email' },
  { title: 'Role', key: 'role' },
  { title: 'Is Active', key: 'is_active' },
  { title: 'Actions', key: 'actions' },
];

onBeforeMount(async () => {
  await userStore.getUsers();
  await userStore.getUserRoles();
});
</script>

<template>
  <UserDialog
    v-model:isDialogVisibble="isDialogVisible"
    :roleOptions="userRoleOptions"
    :formData="formData"
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
