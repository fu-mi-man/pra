<!-- components/atoms/inputs/PriceTextField.vue -->
<template>
  <validated-string-number-field
    :value="value"
    :required="true"
    :min="min"
    :max="max"
    class=""
    dense
    outlined
    suffix="円"
    maxlength="9"
    hide-details="auto"
  />
</template>

<script>
import ValidatedStringNumberField from '@/components/atoms/inputs/base/ValidatedStringNumberField.vue'

export default {
  name: 'PriceTextField',
  components: {
    ValidatedStringNumberField
  },
  data() {
    return {
      localValue: this.value,
    }
  },
  watch: {
    value(newValue) {
      this.localValue = newValue
    }
  },
  props: {
    value: {
      type: String,
      default: ''
    },
    min: {
      type: Number,
      default: 1
    },
    max: {
      type: Number,
      default: 999999999
    },
  },
}
</script>
