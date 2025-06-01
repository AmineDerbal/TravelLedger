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
          <td>{{ item.amount }} DZD</td>
        </tr>
      </template>
    </VDataTable>
  </VCardText>
</template>
