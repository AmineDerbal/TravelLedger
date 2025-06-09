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

  selectOptions: {
    type: Array,
    required: true,
  },

  initialData: {
    type: Object,
    required: true,
  },

  isEdit: {
    type: Boolean,
    required: false,
    default: false,
  },
});

const emit = defineEmits([
  'update:isDialogVisible',
  'submit',
  'update',
  'closeEditDialog',
]);

const onSubmit = () => {
  const { user_id, amount, date, description } = form;

  let payLoad = {
    ledger_category_id: form.category.id,
    user_id,
    amount,
    profit: form.profit ? form.profit : null,
    date,
    description,
  };

  if (props.isEdit) {
    payLoad = {
      ...payLoad,
      ledger_id: form.ledger.id,
      user_id: form.user.id,
    };
  }

  emit('submit', payLoad, props.isEdit);
};

const dialogVisibleUpdate = (val) => {
  emit('update:isDialogVisible', val);
};

const handleCloseEditDialog = () => {
  emit('closeEditDialog');
};

const isVisible = computed({
  get: () => props.isDialogVisible,
  set: (val) => emit('update:isDialogVisible', val),
});

const form = reactive({ ...props.initialData });

const baseIsFormValid = () => {
  return form.ledger &&
    form.type &&
    form.category &&
    form.amount > 0 &&
    form.date &&
    form.description &&
    form.description.length <= 80
    ? true
    : false;
};

const isFormValid = computed(() => {
  return form.ledger?.name === 'RTW' && form.type?.label === 'Debit'
    ? baseIsFormValid() && form.profit > 0
    : baseIsFormValid();
});

const filteredCategories = computed(() => {
  if (!form.type) return [];

  return props.selectOptions
    .find((ledger) => ledger.id === form.ledger.id)
    .categories.filter((category) => category.type.value === form.type.value);
});

watch([() => form.type, () => form.ledger], () => {
  form.category = null;
});
const formatCurrency = (value) => {
  return Math.abs(value);
};

watch(
  () => form.amount,
  (newValue) => {
    form.amount = formatCurrency(newValue);
  },
);

watch(
  () => form.profit,
  (newValue) => {
    form.profit = formatCurrency(newValue);
  },
);
</script>

<template>
  <VDialog
    v-model="isVisible"
    max-width="600"
    @click:outside="
      isEdit ? handleCloseEditDialog() : dialogVisibleUpdate(false)
    "
  >
    <template #activator="{ props }">
      <VBtn
        v-bind="props"
        variant="tonal"
        class="mb-2"
        >{{ props.isEdit ? 'Edit ' : 'Add ' }}Transaction</VBtn
      >
    </template>
    <DialogCloseBtn
      @click="isEdit ? handleCloseEditDialog() : dialogVisibleUpdate(false)"
    />
    <VCard title="Transaction">
      <VCardText>
        <VRow>
          <VCol cols="12">
            <AppSelect
              v-model="form.ledger"
              :hint="form.ledger?.name"
              label="Ledger"
              :items="selectOptions"
              item-title="name"
              item-value="id"
              persistent-hint
              return-object
              single-line
              placeholder="Select Ledger"
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
              :disabled="!form.ledger"
            />
          </VCol>
          <VCol cols="12">
            <AppSelect
              v-model="form.category"
              :hint="form.category?.label"
              label="Category"
              :items="filteredCategories"
              item-title="name"
              item-value="id"
              persistent-hint
              return-object
              single-line
              placeholder="Select Category"
              :disabled="!form.type"
            />
          </VCol>
          <VCol cols="12">
            <AppTextField
              v-model="form.amount"
              type="number"
              suffix="DZD"
              min="0"
              label="Amount"
              placeholder="Amount"
              :disabled="!form.category"
            />
          </VCol>
          <VCol
            cols="12"
            v-if="form.ledger?.name === 'RTW' && form.type?.label === 'Debit'"
          >
            <AppTextField
              v-model="form.profit"
              type="number"
              suffix="DZD"
              min="0"
              label="Profit"
              placeholder="Profit"
              :disabled="!form.category"
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
              :disabled="!form.category"
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
              :disabled="!form.category"
            />
          </VCol>
        </VRow>
      </VCardText>
      <VCardText class="d-flex justify-end flex-wrap gap-3">
        <VBtn
          variant="tonal"
          color="secondary"
          @click="isEdit ? handleCloseEditDialog() : dialogVisibleUpdate(false)"
        >
          Close
        </VBtn>
        <VBtn
          @click="onSubmit"
          :disabled="!isFormValid"
        >
          {{ props.isEdit ? 'Update' : 'Submit' }}
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
</template>
