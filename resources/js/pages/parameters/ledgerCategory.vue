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
const errors = computed(() => ledgerCategoryStore.errors);

const isDialogVisible = ref(false);
const dialogSubmitLoading = ref(false);
const dialogKey = ref(0);
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

const increaseDialogKey = () => {
  dialogKey.value += 1;
};
const resetDialog = () => {
  isDialogVisible.value = false;
  formData.value = { ...defaultFormData };
  increaseDialogKey();
};
const handleSubmit = async (data) => {
  dialogSubmitLoading.value = true;
  const response = await ledgerCategoryStore.storeLedgerCategory(data);
  const expectedStatus = 201;

  if (response.status === expectedStatus) {
    resetDialog();
  } else {
    dialogSubmitLoading.value = false;
    console.log(errors.value);
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
    :dialogSubmitLoading="dialogSubmitLoading"
    :errors="errors"
    @submit="handleSubmit"
    :key="dialogKey"
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
