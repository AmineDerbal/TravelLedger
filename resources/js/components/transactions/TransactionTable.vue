<script setup>
import { formatDateToDdMmYyyy } from '@/utils/dates';
import { useAbility } from '@/plugins/casl/composables/useAbility';

const props = defineProps({
  transactions: {
    type: Array,
    required: true,
  },
  headers: {
    type: Array,
    required: true,
  },
  user: {
    type: Object,
    required: true,
  },
  isAdmin: {
    type: Boolean,
    default: false,
  },
});

const ability = useAbility();

const emit = defineEmits([
  'openEditDialog',
  'closeDialog',
  'deleteTransaction',
  'toggleTransactionStatus',
]);

const handleDelete = (id) => {
  emit('deleteTransaction', id);
};

const openEditDialog = (transaction) => {
  emit('openEditDialog', transaction);
};

const toggleTransactionStatus = (id) => {
  emit('toggleTransactionStatus', id);
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
          <td>{{ item.ledger.name }}</td>
          <td v-if="props.isAdmin">
            <VSwitch
              v-model="item.is_active"
              @click.stop.prevent
              @click="toggleTransactionStatus(item.id)"
            />
          </td>
          <td>
            <div class="d-flex gap-1">
              <IconBtn
                @click="openEditDialog(item)"
                v-if="
                  canEditOrDestroy(
                    'edit_own',
                    'Transaction',
                    item,
                    user,
                    ability,
                  )
                "
              >
                <VIcon icon="tabler-edit" />
              </IconBtn>
              <IconBtn
                @click="handleDelete(item.id)"
                v-if="
                  canEditOrDestroy(
                    'destroy_own',
                    'Transaction',
                    item,
                    user,
                    ability,
                  )
                "
              >
                <VIcon icon="tabler-trash" />
              </IconBtn>
            </div>
          </td>
        </tr>
      </template>
    </VDataTable>
  </VCardText>
</template>
