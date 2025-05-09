<template></template>

<script setup>
import { definePage } from 'vue-router/auto';

definePage({
  meta: {
    requiresAuth: true,
  },
});
</script>
