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
  { title: 'Actions', key: 'actions' },
];

onBeforeMount(async () => {
  await ledgerStore.getLedgers();
});
</script>

<template>
  <VCard>
    <LedgerTable
      :ledgers="ledgers"
      :headers="headers"
    />
  </VCard>
</template>
