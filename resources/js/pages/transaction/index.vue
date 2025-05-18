<script setup>
import { definePage } from 'vue-router/auto';
import useTransactionStore from '@/store/transaction';
import { computed, onBeforeMount, reactive, ref } from 'vue';
import useLedgerStore from '@/store/ledger';

definePage({
  meta: {
    requiresAuth: true,
  },
});

const transactionStore = useTransactionStore();

const transactions = computed(() => {
  return transactionStore.transactions;
});

const ledgerStore = useLedgerStore();
const transactionCategories = computed(() => {
  return transactionStore.categories;
});
const transactionTypes = computed(() => {
  return transactionStore.types;
});

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

const submitForm = async () => {};

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
  <!-- <VBtn variant="tonal">Add Transaction</VBtn> -->
  <VDialog
    v-model="isDialogVisible"
    max-width="600"
  >
    <template #activator="{ props }">
      <VBtn
        v-bind="props"
        variant="tonal"
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
              :config="{ altFormat: 'F j, Y', altInput: true }"
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
  <div v-if="transactions.length < 1">
    <h2>There are no transactions</h2>
  </div>
  <div v-else></div>
</template>
