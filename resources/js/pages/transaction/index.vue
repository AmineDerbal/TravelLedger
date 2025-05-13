<script setup>
import { definePage } from 'vue-router/auto';
import useTransactionStore from '@/store/transaction';
import { computed, ref } from 'vue';
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

const isDialogVisible = ref(false);
const transactionForm = ref({
  ledger: '',
  name: '',
  amount: 0,
  type: '',
  category: '',
});
</script>

<template>
  <VBtn variant="tonal">Add Transaction</VBtn>
  <div v-if="transactions.length < 1">
    <h2>There are no transactions</h2>
  </div>
  <div v-else></div>
</template>
