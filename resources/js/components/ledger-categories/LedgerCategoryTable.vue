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
          <td>{{ item.ledger.name }}</td>
          <td>
            <div class="d-flex gap-1">
              <IconBtn>
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
</template>
