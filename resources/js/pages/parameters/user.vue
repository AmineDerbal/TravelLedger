<script setup>
import useUserStore from '@/store/userStore';

definePage({
  meta: {
    requiresAuth: true,
    action: 'manage',
    subject: 'User',
  },
});

const userStore = useUserStore();
const users = computed(() => userStore.users);

const headers = [
  { title: 'Name', key: 'name' },
  { title: 'Email', key: 'email' },
  { title: 'Role', key: 'role' },
  { title: 'Is Active', key: 'is_active' },
  { title: 'Actions', key: 'actions' },
];

onBeforeMount(async () => {
  await userStore.getUsers();
  console.log(users.value);
});
</script>

<template>
  <VCard>
    <UserTable
      :users="users"
      :headers="headers"
    />
  </VCard>
</template>
