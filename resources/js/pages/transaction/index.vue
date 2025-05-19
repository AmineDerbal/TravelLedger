<script setup>
import { definePage } from 'vue-router/auto';
import useTransactionStore from '@/store/transaction';
import useLedgerStore from '@/store/ledger';
import useUserStore from '@/store/user';

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

const todayDate = new Date().toISOString().split('T')[0];
const yesterdayDate = new Date(new Date().setDate(new Date().getDate() - 1))
  .toISOString()
  .split('T')[0];
const startDate = ref(yesterdayDate);
const endDate = ref(todayDate);
const isDialogVisible = ref(false);
const transactionForm = reactive({
  ledger: ledgerStore.ledger.id,
  amount: 0,
  type: null,
  category: null,
  date: null,
  description: null,
});

const filteredCategories = computed(() => {
  if (!transactionForm.type) return [];
  return transactionCategories.value.filter(
    (category) => category.type === transactionForm.type.value,
  );
});

const isFormValid = computed(() => {
  const form = transactionForm;

  return form.ledger &&
    form.amount > 0 &&
    form.type &&
    form.category &&
    form.date &&
    form.description
    ? true
    : false;
});

const clearForm = () => {
  transactionForm.ledger = ledgerStore.ledger.id;
  transactionForm.amount = 0;
  transactionForm.type = null;
  transactionForm.category = null;
  transactionForm.date = null;
  transactionForm.description = null;
};

const submitForm = async () => {
  const data = {
    ledger_id: transactionForm.ledger,
    user_id: user.value.id,
    amount: transactionForm.amount,
    type: transactionForm.type.value,
    category: transactionForm.category.value,
    date: transactionForm.date,
    description: transactionForm.description,
  };

  const response = await transactionStore.storeTransaction(data);

  if (response.status === 201) {
    await ledgerStore.UpdateLedgerAmount(transactionForm.ledger);
    isDialogVisible.value = false;
    clearForm();
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

watch(
  () => transactionForm.type,
  () => {
    transactionForm.category = null;
  },
);

watch(
  () => transactionForm.amount,
  () => {
    if (transactionForm.amount < 0)
      transactionForm.amount = transactionForm.amount * -1;
  },
);

onBeforeMount(async () => {
  await transactionStore.getTransactionTypes();
  await transactionStore.getTransactionCategories();
});
</script>

<template>
  <VDialog
    v-model="isDialogVisible"
    max-width="600"
  >
    <template #activator="{ props }">
      <VBtn
        v-bind="props"
        variant="tonal"
        class="mb-2"
        >Add Transaction</VBtn
      >
    </template>
    <DialogCloseBtn @click="isDialogVisible = false" />
    <VCard title="Transaction">
      <VCardText>
        <VRow>
          <VCol cols="12">
            <AppTextField
              v-model="transactionForm.amount"
              type="number"
              suffix="DZD"
              min="0"
              label="Amount"
              placeholder="Amount"
            />
          </VCol>
          <VCol cols="12">
            <AppDateTimePicker
              v-model="transactionForm.date"
              label="Date"
              placeholder="Select Date"
              :config="{
                altFormat: 'F j, Y',
                altInput: true,
                maxDate: new Date().toISOString().split('T')[0],
              }"
            />
          </VCol>
          <VCol cols="12">
            <AppTextarea
              v-model="transactionForm.description"
              label="Description"
              placeholder="Description"
              auto-grow
            />
          </VCol>
          <VCol cols="12">
            <AppSelect
              v-model="transactionForm.type"
              :hint="transactionForm.type?.label"
              label="Type"
              :items="transactionTypes"
              item-title="label"
              item-value="value"
              persistent-hint
              return-object
              single-line
              placeholder="Select Type"
            />
          </VCol>
          <VCol
            cols="12"
            v-if="transactionForm.type"
          >
            <AppSelect
              v-model="transactionForm.category"
              :hint="transactionForm.category?.label"
              label="Category"
              :items="filteredCategories"
              item-title="label"
              item-value="value"
              persistent-hint
              return-object
              single-line
              placeholder="Select Category"
            />
          </VCol>
        </VRow>
      </VCardText>
      <VCardText class="d-flex justify-end flex-wrap gap-3">
        <VBtn
          variant="tonal"
          color="secondary"
          @click="isDialogVisible = false"
        >
          Close
        </VBtn>
        <VBtn
          @click="submitForm"
          :disabled="!isFormValid"
        >
          Save
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
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
  <VCard>
    <VCardItem>
      <VCardTitle>Transactions</VCardTitle>
    </VCardItem>
    <VCardText>
      <VDataTable
        :items="transactions"
        :headers="[
          { title: 'Date', key: 'date' },
          { title: 'Description', key: 'description' },
          { title: 'Amount', key: 'amount' },
          { title: 'Category', key: 'category' },
          { title: 'Type', key: 'type' },
        ]"
        class="text-no-wrap"
      >
        <template #item="{ item }">
          <tr>
            <td>{{ item.date }}</td>
            <td>{{ item.description }}</td>
            <td>{{ item.amount }}</td>
            <td>{{ item.category.label }}</td>
            <td>{{ item.type.label }}</td>
          </tr>
        </template>
      </VDataTable>
    </VCardText>
  </VCard>
</template>
