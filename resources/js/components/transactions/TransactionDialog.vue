<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  transactionTypes: {
    type: Array,
    required: true,
  },
  transactionCategories: {
    type: Array,
    required: true,
  },
  isEdit: {
    type: Boolean,
    required: false,
    default: false,
  },
});

const emit = defineEmits(['update:isDialogVisible', 'submit']);

const dialogVisibleUpdate = (val) => {
  emit('update:isDialogVisible', val);
};

const handleSubmit = () => {
  emit('submit', {
    ...form,
    category: form.category.value,
    type: form.type.value,
  });
};

const isVisible = computed({
  get: () => props.isDialogVisible,
  set: (val) => emit('update:isDialogVisible', val),
});

const defaultForm = {
  amount: 0,
  date: null,
  description: '',
  category: null,
  type: null,
};

const form = reactive({ ...defaultForm });

const isFormValid = computed(() => {
  return form.amount > 0 &&
    form.date &&
    form.type &&
    form.category &&
    form.description &&
    form.description.length <= 80
    ? true
    : false;
});

const filteredCategories = computed(() => {
  if (!form.type) return [];

  return props.transactionCategories.filter((category) => {
    return category.type === form.type.value;
  });
});

watch(
  () => form.type,
  () => {
    form.category = null;
  },
);

watch(
  () => form.amount,
  () => {
    if (form.amount < 0) form.amount = form.amount * -1;
  },
);
</script>

<template>
  <VDialog
    v-model="isVisible"
    max-width="600"
  >
    <template #activator="{ props }">
      <VBtn
        v-bind="props"
        variant="tonal"
        class="mb-2"
        >{{ props.isEdit ? 'Edit ' : 'Add ' }}Transaction</VBtn
      >
    </template>
    <DialogCloseBtn @click="dialogVisibleUpdate(false)" />
    <VCard title="Transaction">
      <VCardText>
        <VRow>
          <VCol cols="12">
            <AppTextField
              v-model="form.amount"
              type="number"
              suffix="DZD"
              min="0"
              label="Amount"
              placeholder="Amount"
            />
          </VCol>
          <VCol cols="12">
            <AppDateTimePicker
              v-model="form.date"
              label="Date"
              placeholder="Select Date"
              :config="{
                altFormat: 'F j, Y',
                altInput: true,
                maxDate: new Date().toISOString().split('T')[0],
              }"
            />
          </VCol>
          <VCol cols="12">
            <AppTextarea
              v-model="form.description"
              label="Description"
              placeholder="Description"
              counter="80"
              rows="2"
              :rules="[
                (v) =>
                  v.length <= 80 ||
                  'Description must be less than 80 characters',
              ]"
            />
          </VCol>
          <VCol cols="12">
            <AppSelect
              v-model="form.type"
              :hint="form.type?.label"
              label="Type"
              :items="transactionTypes"
              item-title="label"
              item-value="value"
              persistent-hint
              return-object
              single-line
              placeholder="Select Type"
            />
          </VCol>
          <VCol
            cols="12"
            v-if="form.type"
          >
            <AppSelect
              v-model="form.category"
              :hint="form.category?.label"
              label="Category"
              :items="filteredCategories"
              item-title="label"
              item-value="value"
              persistent-hint
              return-object
              single-line
              placeholder="Select Category"
            />
          </VCol>
        </VRow>
      </VCardText>
      <VCardText class="d-flex justify-end flex-wrap gap-3">
        <VBtn
          variant="tonal"
          color="secondary"
          @click="dialogVisibleUpdate(false)"
        >
          Close
        </VBtn>
        <VBtn
          @click="handleSubmit"
          :disabled="!isFormValid"
        >
          Save
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
</template>
