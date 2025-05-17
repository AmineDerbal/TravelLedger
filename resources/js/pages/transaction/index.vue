<script setup>
import { definePage } from 'vue-router/auto';
import useTransactionStore from '@/store/transaction';
import { computed, onBeforeMount, ref } from 'vue';
import useLedgerStore from '@/store/ledger';
import DialogCloseBtn from '@/@core/components/DialogCloseBtn.vue';
import AppSelect from '@/@core/components/app-form-elements/AppSelect.vue';

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
const transactionForm = ref({
  ledger: '',
  amount: 0,
  type: null,
  category: null,
  date: '',
});

onBeforeMount(async () => {
  await transactionStore.getTransactionTypes();
  await transactionStore.getTransactionCategories();
  console.log(transactionCategories.value);
  console.log(transactionTypes.value);
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
          <VCol
            cols="12"
            md="6"
          >
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
            md="6"
            v-if="transactionForm.type"
          >
            <AppSelect
              v-model="transactionForm.category"
              :hint="transactionForm.category?.label"
              label="Category"
              :items="transactionCategories"
              item-title="label"
              item-value="value"
              persistent-hint
              return-object
              single-line
              placeholder="Select Category"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <AppTextField
              v-model="transactionForm.amount"
              label="Amount"
              placeholder="Amount"
            />
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </VDialog>
  <div v-if="transactions.length < 1">
    <h2>There are no transactions</h2>
  </div>
  <div v-else></div>
</template>
