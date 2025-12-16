<script setup>
const props = defineProps({
  ledgerCategories: {
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
  'closeEditDialog',
  'deleteLedgerCategory',
]);

const handleDelete = (id) => {
  emit('deleteLedgerCategory', id);
};
const openEditDialog = (ledgerCategoryData) => {
  emit('openEditDialog', ledgerCategoryData);
};
</script>

<template>
  <VCardText>
    <VDataTable
      :items="props.ledgerCategories"
      :headers="props.headers"
      class="text-no-wrap"
    >
      <template #item="{ item }">
        <tr>
          <td>{{ item.name }}</td>
          <td>{{ item.type.label }}</td>
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
