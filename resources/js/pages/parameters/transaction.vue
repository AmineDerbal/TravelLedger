<script setup>
import useTransactionStore from '@/store/transaction';
import useLedgerStore from '@/store/ledgerStore';
import useUserStore from '@/store/userStore';
import { getBalanceData, getTableHeaders } from '@/utils/transactionMeta';
import { onBeforeMount } from 'vue';

definePage({
  meta: {
    requiresAuth: true,
    action: 'edit',
    subject: 'Transaction',
  },
});

const transactionStore = useTransactionStore();
const userStore = useUserStore();
const ledgerStore = useLedgerStore();

const user = computed(() => userStore.userData);
const transactions = computed(() => transactionStore.transactions || []);
const balance = computed(() => transactionStore.balance || {});

const balanceData = computed(() =>
  getBalanceData(transactions.value, balance.value),
);

const headers = [
  ...getTableHeaders().slice(0, -1),
  { title: 'Is Active', key: 'is_active' },
  getTableHeaders().slice(-1)[0],
];

const toggleTransactionStatus = async (id) => {
  const response = await transactionStore.toggleTransactionStatus(id);
  if (response.status === 200) {
    await transactionStore.getAllTransactions();
  }
};

onBeforeMount(async () => {
  await transactionStore.getAllTransactions();
});
</script>

<template>
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
    <TransactionTable
      :transactions="transactions"
      :headers="headers"
      :user="user"
      :isAdmin="true"
      @toggleTransactionStatus="toggleTransactionStatus"
      :key="transactions"
    />
  </VCard>
</template>
