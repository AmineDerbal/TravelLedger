<script setup>
import { formatDateToDdMmYyyy } from '@/utils/dates';
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

const emit = defineEmits([
  'openEditDialog',
  'closeDialog',
  'deleteTransaction',
]);

const handleDelete = (id) => {
  emit('deleteTransaction', id);
};

const openEditDialog = (transaction) => {
  emit('openEditDialog', transaction);
};

const resolveTypeLabel = (typeMSG) => {
  if (typeMSG === 'Debit') {
    return {
      text: 'Debit',
      color: 'error',
    };
  } else {
    return {
      text: 'Credit',
      color: 'success',
    };
  }
};
const resolveCategoryLabel = (categoryMSG) => {
  if (categoryMSG === 'Flight') {
    return {
      text: 'Flight',
      color: 'primary',
    };
  }
  if (categoryMSG === 'Hotel') {
    return {
      text: 'Hotel',
      color: 'warning',
    };
  }
  if (categoryMSG === 'Visa') {
    return {
      text: 'Visa',
      color: 'info',
    };
  }
  if (categoryMSG === 'Insurance') {
    return {
      text: 'Insurance',
      color: 'error',
    };
  }
  if (categoryMSG === 'Rechargement') {
    return {
      text: 'Rechargement',
      color: 'success',
    };
  }
  if (categoryMSG === 'Refund') {
    return {
      text: 'Refund',
      color: 'secondary',
    };
  }
  return {
    text: categoryMSG,
    color: 'info',
  };
};
</script>

<template>
  <VCardText>
    <VDataTable
      :items="props.transactions"
      :headers="props.headers"
      class="text-no-wrap"
    >
      <template #item="{ item }">
        <tr>
          <td>{{ item.user.name }}</td>
          <td>{{ formatDateToDdMmYyyy(item.date) }}</td>
          <td>{{ item.description }}</td>
          <td>{{ item.amount }} DZD</td>
          <td>
            <VChip
              v-bind="resolveTypeLabel(item.type.label)"
              density="default"
              label
              size="small"
            />
          </td>
          <td>
            <VChip
              v-bind="resolveCategoryLabel(item.category.name)"
              density="comfortable"
              label
              size="small"
            />
          </td>
          <td>
            <div class="d-flex gap-1">
              <IconBtn @click="openEditDialog(item)">
                <VIcon icon="tabler-edit" />
              </IconBtn>
              <IconBtn @click="handleDelete(item.id)">
                <VIcon icon="tabler-trash" />
              </IconBtn>
            </div>
          </td>
        </tr>
      </template>
    </VDataTable>
  </VCardText>
</template>
