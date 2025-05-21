<script setup>
import { definePage } from 'vue-router/auto';
import useTransactionStore from '@/store/transaction';
import useLedgerStore from '@/store/ledger';
import useUserStore from '@/store/user';
import { getTodayDate, getYesterdayDate } from '@/utils/dates';
import TransactionTable from '@/components/transactions/TransactionTable.vue';

definePage({
  meta: {
    requiresAuth: true,
  },
});

const transactionStore = useTransactionStore();
const ledgerStore = useLedgerStore();
const userStore = useUserStore();

const user = computed(() => {
  return userStore.userData;
});

const transactions = computed(() => {
  return transactionStore.transactions;
});

const transactionCategories = computed(() => {
  return transactionStore.categories;
});
const transactionTypes = computed(() => {
  return transactionStore.types;
});

const startDate = ref(getYesterdayDate());
const endDate = ref(getTodayDate());
const isDialogVisible = ref(false);
const isEdit = ref(false);
const dialogKey = ref(0);

const headers = [
  { title: 'Date', key: 'date' },
  { title: 'Description', key: 'description', sortable: false },
  { title: 'Amount', key: 'amount' },
  { title: 'Category', key: 'category.value' },
  { title: 'Type', key: 'type.value' },
  { title: 'Actions', key: 'actions', sortable: false },
];

const defaultForm = {
  amount: 0,
  date: null,
  description: '',
  category: null,
  type: null,
};

const initialData = ref({ ...defaultForm });

const increaseDialogKey = () => {
  dialogKey.value += 1;
};

const resetDialog = () => {
  isDialogVisible.value = false;
  increaseDialogKey();
};

const openEditDialog = (transaction) => {
  initialData.value = { ...transaction };
  isEdit.value = true;
  isDialogVisible.value = true;
  increaseDialogKey();
};

const closeEditDialog = () => {
  initialData.value = { ...defaultForm };
  isEdit.value = false;
  resetDialog();
};

const submitForm = async (data) => {
  data = {
    ...data,
    user_id: user.value.id,
    ledger_id: ledgerStore.ledger.id,
  };

  const response = await transactionStore.storeTransaction(data);

  if (response.status === 201) {
    await ledgerStore.UpdateLedgerAmount(ledgerStore.ledger.id);
    isDialogVisible.value = false;
    dialogKey.value += 1;
  }
};

const fetchTransactionsByDateRange = async () => {
  const data = {
    ledger_id: ledgerStore.ledger.id,
    start_date: startDate.value,
    end_date: endDate.value,
  };

  await transactionStore.getTransactionsByDateRange(data);
};

onBeforeMount(async () => {
  await transactionStore.getTransactionTypes();
  await transactionStore.getTransactionCategories();
});
</script>

<template>
  <TransactionDialog
    v-model:isDialogVisible="isDialogVisible"
    :transactionTypes="transactionTypes"
    :transactionCategories="transactionCategories"
    :initialData="initialData"
    :isEdit="isEdit"
    @submit="submitForm"
    @closeEditDialog="closeEditDialog"
    :key="dialogKey"
  />

  <VCard class="mb-6 pa-2">
    <VRow class="align-end">
      <VCol
        cols="9"
        md="4"
      >
        <AppDateTimePicker
          v-model="startDate"
          label="Start Date"
          placeholder="Select Start Date"
          :config="{
            altFormat: 'F j, Y',
            altInput: true,
            maxDate: new Date().toISOString().split('T')[0],
          }"
        />
      </VCol>
      <VCol
        cols="9"
        md="4"
      >
        <AppDateTimePicker
          v-model="endDate"
          label="End Date"
          placeholder="Select End Date"
          :config="{
            altFormat: 'F j, Y',
            altInput: true,
            maxDate: new Date().toISOString().split('T')[0],
          }"
        />
      </VCol>
      <VCol
        cols="9"
        md="4"
      >
        <VBtn
          variant="tonal"
          color="primary"
          @click="fetchTransactionsByDateRange"
          >Filter</VBtn
        ></VCol
      >
    </VRow>
  </VCard>
  <TransactionTable
    :transactions="transactions"
    :headers="headers"
    @openEditDialog="openEditDialog"
  />
</template>
