<script setup>
import { useAbility } from '@/plugins/casl/composables/useAbility';
import useLedgerStore from '@/store/ledgerStore';
definePage({
  meta: {
    requiresAuth: true,
    action: 'view',
    subject: 'Ledger',
  },
});

const ledgerStore = useLedgerStore();
const ability = useAbility();

const ledgers = computed(() => ledgerStore.ledgers);
const errors = computed(() => ledgerStore.errors);

const isDialogVisible = ref(false);
const isEdit = ref(false);

const defaultFormData = {
  name: '',
};

const formData = ref({ ...defaultFormData });

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
  <LedgerDialog v-model:isDialogVisible="isDialogVisible" />
  <VCard title="Ledgers">
    <VCardText>
      <VRow class="align-end">
        <VCol
          cols="12"
          md="4"
        >
          <VBtn
            v-if="ability.can('manage', 'Ledger')"
            variant="tonal"
            color="primary"
            @click="isDialogVisible = true"
            >Add Ledger</VBtn
          >
        </VCol>
      </VRow>
    </VCardText>
    <VDivider />
    <LedgerTable
      :ledgers="ledgers"
      :headers="headers"
    />
  </VCard>
</template>
