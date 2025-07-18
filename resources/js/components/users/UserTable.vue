<script setup>
const props = defineProps({
  users: {
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
      :items="props.users"
      :headers="props.headers"
      class="text-no-wrap"
    >
      <template #item="{ item }">
        <tr>
          <td>{{ item.name }}</td>
          <td>{{ item.email }}</td>
          <td>{{ item.role }}</td>
          <td>{{ item.is_active }}</td>
          <td></td>
        </tr>
      </template>
    </VDataTable>
  </VCardText>
</template>
