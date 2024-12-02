<!-- @/components/atoms/PriceTextTextField.vue -->
<template>
  <validated-text-field
    :value="value"
    dense
    outlined
    maxlength="10"
    counter
    hide-details="auto"
    placeholder="例：オープン価格"
  />
</template>

<script>
import ValidatedTextField from '@/components/atoms/inputs/base/ValidatedTextField.vue'

export default {
  name: 'PriceTextTextField',
  components: {
    ValidatedTextField
  },
  props: {
    value: {
      type: String,
      default: '',
    }
  },
}
</script>
