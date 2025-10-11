<script setup>
import useTransactionStore from '@/store/transaction';
import useLedgerStore from '@/store/ledgerStore';
import useUserStore from '@/store/userStore';
import { getTodayDate, getYesterdayDate } from '@/utils/dates';
import { getBalanceData } from '@/utils/transactionMeta';
import { displayToast } from '@/utils/toast';

definePage({
  meta: {
    requiresAuth: true,
    action: 'view',
    subject: 'Transaction',
  },
});

const transactionStore = useTransactionStore();
const ledgerStore = useLedgerStore();
const userStore = useUserStore();

const user = computed(() => userStore.userData);
const transactions = computed(() => transactionStore.transactions);
const balance = computed(() => transactionStore.balance);
const transactionTypes = computed(() => transactionStore.types);
const selectOptions = computed(() => ledgerStore.ledgersWithCategories);

const startDate = ref(getYesterdayDate());
const endDate = ref(getTodayDate());
const selectedLedger = ref(selectOptions.value[0]);
const isDialogVisible = ref(false);
const isEdit = ref(false);
const dialogKey = ref(0);
const loadings = ref([]);

const rangeDateData = computed(() => ({
  start_date: startDate.value,
  end_date: endDate.value,
  ledger_id: selectedLedger.value['id'],
}));

const balanceData = computed(() =>
  getBalanceData(transactions.value, balance.value),
);

const headers = [
  { title: 'User', key: 'user.name' },
  { title: 'Date', key: 'date' },
  { title: 'Description', key: 'description', sortable: false },
  { title: 'Amount', key: 'amount' },
  { title: 'Type', key: 'type.value' },
  { title: 'Category', key: 'category.value' },
  { title: 'Ledger', key: 'ledger.name' },
  { title: 'Actions', key: 'actions', sortable: false },
];

const defaultForm = computed(() => ({
  user_id: user.value.id,
  ledger_category_id: null,
  ledger_id: null,
  type: null,
  date: null,
  description: '',
  amount: 0,
  profit: null,
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
  console.log(data);
  const response = isUpdating
    ? await transactionStore.updateTransaction(data)
    : await transactionStore.storeTransaction(data);
  const expectedStatus = isUpdating ? 200 : 201;
  displayToast(expectedStatus, response.status, response.data.message);

  if (response.status === expectedStatus) {
    await ledgerStore.UpdateLedgerBalance(ledgerStore.ledger.id);
    if (isUpdating)
      await transactionStore.getTransactionsByDateRange(rangeDateData.value);
    resetDialog();
  }
};

const handleTransactionDelete = async (id) => {
  const response = await transactionStore.deactivateTransaction(id);
  const expectedStatus = 200;
  displayToast(expectedStatus, response.status, response.data.message);
  if (response.status === expectedStatus) {
    await ledgerStore.UpdateLedgerBalance(ledgerStore.ledger.id);
    await transactionStore.getTransactionsByDateRange(rangeDateData.value);
  }
};

const fetchTransactionsByDateRange = async (i) => {
  loadings.value[i] = true;
  const response = await transactionStore.getTransactionsByDateRange(
    rangeDateData.value,
  );
  if (response.status) loadings.value[i] = false;
};

const downloadExcelTransactions = async (i) => {
  loadings.value[i] = true;
  const data = {
    transactions: transactions.value,
    balance: {
      totalDebit: balance.value.totalDebit,
      totalCredit: balance.value.totalCredit,
      totalBalance: balance.value.totalBalance,
    },
    exportDate: {
      start_date: startDate.value,
      end_date: endDate.value,
    },
  };

  const response = await transactionStore.downloadExcelTransactions(data);
  if (response.status) loadings.value[i] = false;
};

onBeforeMount(async () => {
  await transactionStore.getTransactionTypes();
  await transactionStore.getTransactionCategories();
  await ledgerStore.getLedgersWithCategories();
});
</script>

<template>
  <TransactionDialog
    v-model:isDialogVisible="isDialogVisible"
    :transactionTypes="transactionTypes"
    :initialData="initialData"
    :selectOptions="selectOptions"
    :isEdit="isEdit"
    @submit="handleTranactionSubmit"
    @closeEditDialog="resetDialog"
    :key="dialogKey"
  />

  <VRow class="mb-2 mt-2 match-height">
    <VCol
      v-for="(item, index) in balanceData"
      :key="index"
      cols="12"
      md="3"
      sm="6"
    >
      <VCard>
        <VCardText>
          <div class="d-flex justify-space-between align-center">
            <div class="d-flex flex-column">
              <h5 class="text-h5 mb-1">
                {{ item.value }}
              </h5>
              <div class="text-body-2">{{ item.name }}</div>
            </div>
            <VAvatar
              size="40"
              :color="item.color"
              variant="tonal"
            >
              <VIcon
                size="24"
                :icon="item.icon"
              />
            </VAvatar>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>

  <VCard title="Transactions">
    <VCardText>
      <VRow class="align-end dense">
        <VCol
          cols="12"
          md="3"
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
          md="3"
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
          <AppSelect
            v-model="selectedLedger"
            label="Ledger"
            :items="selectOptions"
            item-title="name"
            item-value="id"
            persistent-hint
            return-object
            single-line
            placeholder="Select Ledger"
          />
        </VCol>
        <VCol
          cols="12"
          md="1"
        >
          <VBtn
            variant="tonal"
            :loading="loadings[0]"
            color="primary"
            @click="fetchTransactionsByDateRange(0)"
            >Filter</VBtn
          >
        </VCol>
        <VCol
          cols="12"
          md="1"
        >
          <VBtn
            variant="tonal"
            :loading="loadings[1]"
            color="success"
            :disabled="!transactions.length || loadings[1]"
            @click="downloadExcelTransactions(1)"
            >Export</VBtn
          >
        </VCol>
      </VRow>
    </VCardText>

    <VDivider />
    <TransactionTable
      :transactions="transactions"
      :headers="headers"
      :user="user"
      @openEditDialog="openEditDialog"
      @deleteTransaction="handleTransactionDelete"
    />
  </VCard>
</template>
