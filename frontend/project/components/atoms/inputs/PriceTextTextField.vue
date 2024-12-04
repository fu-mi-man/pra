<!-- @/components/atoms/inputs/PriceTextTextField.vue -->
<template>
  <validated-text-field
    :value="value"
    :required="true"
    :required-message="'カスタムできる必須です！'"
    :input-max-length="50"
    dense
    outlined
    counter
    maxlength="50"
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
