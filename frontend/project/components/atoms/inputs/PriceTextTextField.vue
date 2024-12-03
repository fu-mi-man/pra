<!-- @/components/atoms/inputs/PriceTextTextField.vue -->
<template>
  <validated-text-field
    v-bind="$attrs"
    :value="value"
    :required="true"
    :input-max-length="10"
    dense
    outlined
    counter
    maxlength="10"
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
