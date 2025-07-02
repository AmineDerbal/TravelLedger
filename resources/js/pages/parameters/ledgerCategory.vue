<script setup>
import { useAbility } from '@/plugins/casl/composables/useAbility';
import useLedgerCategoryStore from '@/store/ledgerCategoryStore';

definePage({
  meta: {
    requiresAuth: true,
  },
});

const ledgerCategoryStore = useLedgerCategoryStore();
const ability = useAbility();

const ledgerCategories = computed(() => ledgerCategoryStore.ledgerCategories);
const ledgerOptions = computed(() => ledgerCategoryStore.LedgerOptions);
const typeOptions = computed(() => ledgerCategoryStore.typeOptions);
const errors = computed(() => ledgerCategoryStore.errors);

const isEdit = ref(false);
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
  isEdit.value = false;
  formData.value = { ...defaultFormData };
  increaseDialogKey();
};

const openEditDialog = (ledgerCategory) => {
  formData.value = {
    id: ledgerCategory.id,
    name: ledgerCategory.name,
    ledger_id: ledgerCategory.ledger,
    type: ledgerCategory.type,
  };

  isEdit.value = true;
  isDialogVisible.value = true;
  increaseDialogKey();
};
const handleSubmit = async (data, isUpdating) => {
  dialogSubmitLoading.value = true;

  const response = isUpdating
    ? await ledgerCategoryStore.updateLedgerCategory(data)
    : await ledgerCategoryStore.storeLedgerCategory(data);

  const expectedStatus = isUpdating ? 200 : 201;

  dialogSubmitLoading.value = false;

  if (response.status === expectedStatus) {
    await ledgerCategoryStore.getLedgerCategories();
    resetDialog();
  }
};

const handleDelete = async (id) => {
  await ledgerCategoryStore.deleteLedgerCategory(id);
  await ledgerCategoryStore.getLedgerCategories();
};

onBeforeMount(async () => {
  await ledgerCategoryStore.getLedgerCategories();
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
    :isEdit="isEdit"
    @submit="handleSubmit"
    @closeEditDialog="resetDialog"
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
            v-if="ability.can('manage', 'Ledger')"
            variant="tonal"
            color="primary"
            @click="isDialogVisible = true"
            >Add Category</VBtn
          >
        </VCol>
      </VRow>
    </VCardText>
    <VDivider />
    <LedgerCategoryTable
      :ledgerCategories="ledgerCategories"
      :headers="headers"
      @openEditDialog="openEditDialog"
      @deleteLedgerCategory="handleDelete"
    />
  </VCard>
</template>
