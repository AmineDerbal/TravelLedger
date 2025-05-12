<template></template>

<script setup>
import { definePage } from 'vue-router/auto';
import { useTransactionStore } from '@/store/transaction';
import { computed } from 'vue';

definePage({
  meta: {
    requiresAuth: true,
  },
});

const transactionStore = useTransactionStore();

const transactions = computed(() => {
  return transactionStore.transactions;
});
</script>

<template>
  <VBtn variant="tonal">Add Transaction</VBtn>
  <div v-if="transactions.length < 1">
    <h2>There are no transactions</h2>
  </div>
  <div v-else></div>
</template>
