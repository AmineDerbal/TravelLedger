<script setup>
const props = defineProps({
  transactions: {
    type: Array,
    required: true,
  },
  headers: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(['openEditDialog', 'closeDialog']);

const openEditDialog = (transaction) => {
  emit('openEditDialog', transaction);
};
</script>

<template>
  <VCard>
    <VCardItem>
      <VCardTitle>Transactions</VCardTitle>
    </VCardItem>
    <VCardText>
      <VDataTable
        :items="props.transactions"
        :headers="props.headers"
        class="text-no-wrap"
      >
        <template #item="{ item }">
          <tr>
            <td>{{ item.date }}</td>
            <td>{{ item.description }}</td>
            <td>{{ item.amount }}</td>
            <td>{{ item.category.label }}</td>
            <td>{{ item.type.label }}</td>
            <td>
              <div class="d-flex gap-1">
                <IconBtn @click="openEditDialog(item)">
                  <VIcon icon="tabler-edit" />
                </IconBtn>
                <IconBtn>
                  <VIcon icon="tabler-trash" />
                </IconBtn>
              </div>
            </td>
          </tr>
        </template>
      </VDataTable>
    </VCardText>
  </VCard>
</template>
