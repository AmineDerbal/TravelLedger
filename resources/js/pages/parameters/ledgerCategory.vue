<script setup>
import useLedgerCategoryStore from '@/store/ledgerCategoryStore';

definePage({
  meta: {
    requiresAuth: true,
  },
});

const ledgerCategoryStore = useLedgerCategoryStore();

const ledgerOptions = computed(() => ledgerCategoryStore.LedgerOptions);
const typeOptions = computed(() => ledgerCategoryStore.typeOptions);

const isDialogVisible = ref(false);
const defaultFormData = {
  name: '',
  ledger_id: null,
  type: null,
};

const formData = ref({ ...defaultFormData });

const headers = [
  { title: 'Name', key: 'name' },
  { title: 'Type', key: 'type' },
  { title: 'Ledger', key: 'ledger' },
  { title: 'Actions', key: 'actions' },
];

const handleSubmit = async (data) => {
  const response = await ledgerCategoryStore.storeLedgerCategory(data);
  const expectedStatus = 201;

  if (response.status === expectedStatus) {
    isDialogVisible.value = false;
  }
};

onBeforeMount(async () => {
  await ledgerCategoryStore.getLedgerCategoryOptions();
});
</script>

<template>
  <LedgerCategoryDialog
    v-model:isDialogVisible="isDialogVisible"
    :ledgerOptions="ledgerOptions"
    :typeOptions="typeOptions"
    :formData="formData"
    @submit="handleSubmit"
  />
  <VCard title="Categories">
    <VCardText>
      <VRow class="align-end">
        <VCol
          cols="12"
          md="4"
        >
          <VBtn
            variant="tonal"
            color="primary"
            @click="isDialogVisible = true"
            >Add Category</VBtn
          >
        </VCol>
      </VRow>
    </VCardText>
  </VCard>
</template>
