<script setup>
import useTransactionStore from '@/store/transaction';
import useLedgerStore from '@/store/ledger';
import useUserStore from '@/store/user';
import { getTodayDate, getYesterdayDate } from '@/utils/dates';

definePage({
  meta: {
    requiresAuth: true,
  },
});

const transactionStore = useTransactionStore();
const ledgerStore = useLedgerStore();
const userStore = useUserStore();

const user = computed(() => userStore.userData);
const transactions = computed(() => transactionStore.transactions);
const transactionCategories = computed(() => transactionStore.categories);
const transactionTypes = computed(() => transactionStore.types);

const startDate = ref(getYesterdayDate());
const endDate = ref(getTodayDate());
const isDialogVisible = ref(false);
const isEdit = ref(false);
const dialogKey = ref(0);

const rangeDateData = computed(() => ({
  start_date: startDate.value,
  end_date: endDate.value,
  ledger_id: ledgerStore.ledger.id,
}));

const headers = [
  { title: 'User', key: 'user.name' },
  { title: 'Date', key: 'date' },
  { title: 'Description', key: 'description', sortable: false },
  { title: 'Amount', key: 'amount' },
  { title: 'Type', key: 'type.value' },
  { title: 'Category', key: 'category.value' },
  { title: 'Actions', key: 'actions', sortable: false },
];

const defaultForm = computed(() => ({
  ledger_id: ledgerStore.ledger.id,
  user_id: user.value.id,
  date: null,
  description: '',
  amount: 0,
  type: null,
  category: null,
}));

const dateConfig = {
  altFormat: 'F j, Y',
  altInput: true,
  maxDate: getTodayDate(),
};

const initialData = ref({ ...defaultForm.value });

const increaseDialogKey = () => {
  dialogKey.value += 1;
};

const resetDialog = () => {
  isDialogVisible.value = false;
  isEdit.value = false;
  initialData.value = { ...defaultForm.value };
  increaseDialogKey();
};

const openEditDialog = (transaction) => {
  initialData.value = { ...transaction };
  isEdit.value = true;
  isDialogVisible.value = true;
  increaseDialogKey();
};

const handleTranactionSubmit = async (data, isUpdating = false) => {
  const response = isUpdating
    ? await transactionStore.updateTransaction(data)
    : await transactionStore.storeTransaction(data);
  const expectedStatus = isUpdating ? 200 : 201;

  if (response.status === expectedStatus) {
    await ledgerStore.UpdateLedgerAmount(ledgerStore.ledger.id);
    if (isUpdating)
      await transactionStore.getTransactionsByDateRange(rangeDateData.value);
    resetDialog();
  }
};

const handleTransactionDelete = async (id) => {
  const response = await transactionStore.deleteTransaction(id);
  if (response.status === 200) {
    await ledgerStore.UpdateLedgerAmount(ledgerStore.ledger.id);
    await transactionStore.getTransactionsByDateRange(rangeDateData.value);
  }
};

const fetchTransactionsByDateRange = async () => {
  await transactionStore.getTransactionsByDateRange(rangeDateData.value);
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
    @submit="handleTranactionSubmit"
    @closeEditDialog="resetDialog"
    :key="dialogKey"
  />

  <VCard title="Transactions">
    <VCardText>
      <VRow class="align-end">
        <VCol
          cols="12"
          md="4"
        >
          <AppDateTimePicker
            v-model="startDate"
            label="Start Date"
            placeholder="Select Start Date"
            :config="dateConfig"
          />
        </VCol>
        <VCol
          cols="12"
          md="4"
        >
          <AppDateTimePicker
            v-model="endDate"
            label="End Date"
            placeholder="Select End Date"
            :config="dateConfig"
          />
        </VCol>
        <VCol
          cols="12"
          md="4"
        >
          <VBtn
            variant="tonal"
            color="primary"
            @click="fetchTransactionsByDateRange"
            >Filter</VBtn
          >
        </VCol>
      </VRow>
    </VCardText>

    <VDivider />
    <TransactionTable
      :transactions="transactions"
      :headers="headers"
      @openEditDialog="openEditDialog"
      @deleteTransaction="handleTransactionDelete"
    />
  </VCard>
</template>
