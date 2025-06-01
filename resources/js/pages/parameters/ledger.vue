<script setup>
import useLedgerStore from '@/store/ledger';
definePage({
  meta: {
    requiresAuth: true,
  },
});

const ledgerStore = useLedgerStore();
const ledgers = computed(() => ledgerStore.ledgers);

const headers = [
  { title: 'Name', key: 'name' },
  { title: 'Amount', key: 'amount' },
];

onBeforeMount(async () => {
  await ledgerStore.getLedgers();
});
</script>

<template>
  <LedgerTable
    :ledgers="ledgers"
    :headers="headers"
  />
</template>
