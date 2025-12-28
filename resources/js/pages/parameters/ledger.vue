<script setup>
import { useAbility } from '@/plugins/casl/composables/useAbility';
import useLedgerStore from '@/store/ledgerStore';

definePage({
  meta: {
    requiresAuth: true,
    action: 'view',
    subject: 'Ledger',
  },
});

const ledgerStore = useLedgerStore();
const ability = useAbility();

const ledgers = computed(() => ledgerStore.ledgers);
const errors = computed(() => ledgerStore.errors);

const isDialogVisible = ref(false);
const isEdit = ref(false);
const dialogSubmitLoading = ref(false);
const dialogKey = ref(0);

const defaultFormData = {
  name: '',
};

const formData = ref({ ...defaultFormData });

const headers = [
  { title: 'Name', key: 'name' },
  { title: 'Amount', key: 'amount' },
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

const openEditDialog = (ledger) => {
  formData.value = {
    id: ledger.id,
    name: ledger.name,
  };

  isEdit.value = true;
  isDialogVisible.value = true;
  increaseDialogKey();
};
const handleSubmit = async (data, isUpdating) => {
  dialogSubmitLoading.value = true;

  const response = isUpdating
    ? await ledgerStore.updateLedger(data)
    : await ledgerStore.storeLedger(data);

  const expectedStatus = isUpdating ? 200 : 201;
  if (response.status === expectedStatus) {
    await ledgerStore.getLedgers();
    resetDialog();
    dialogSubmitLoading.value = false;
  }
};

const handleDelete = async (id) => {
  const response = await ledgerStore.deleteLedger(id);
  if (response.status === 200) {
    await ledgerStore.getLedgers();
  }
};

onBeforeMount(async () => {
  await ledgerStore.getLedgers();
});
</script>

<template>
  <LedgerDialog
    v-model:isDialogVisible="isDialogVisible"
    :dialogSubmitLoading="dialogSubmitLoading"
    :isEdit="isEdit"
    :formData="formData"
    :errors="errors"
    @submit="handleSubmit"
    @closeEditDialog="resetDialog"
    :key="dialogKey"
  />
  <VCard title="Ledgers">
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
            >Add Ledger</VBtn
          >
        </VCol>
      </VRow>
    </VCardText>
    <VDivider />
    <LedgerTable
      :ledgers="ledgers"
      :headers="headers"
      @openEditDialog="openEditDialog"
      @deleteLedger="handleDelete"
    />
  </VCard>
</template>
