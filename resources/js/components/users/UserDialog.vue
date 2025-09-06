<script setup>
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  roleOptions: {
    type: Array,
    required: true,
  },
  formData: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: true,
  },
  dialogSubmitLoading: {
    type: Boolean,
    required: true,
    default: false,
  },

  isEdit: {
    type: Boolean,
    required: false,
    default: false,
  },
});
</script>
