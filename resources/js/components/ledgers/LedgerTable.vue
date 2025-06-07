<script setup>
const props = defineProps({
  ledgers: {
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
      :items="props.ledgers"
      :headers="props.headers"
      class="text-no-wrap"
    >
      <template #item="{ item }">
        <tr>
          <td>{{ item.name }}</td>
          <td>{{ item.balance }} DZD</td>
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
