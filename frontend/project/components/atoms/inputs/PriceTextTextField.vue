<!-- @/components/atoms/inputs/PriceTextTextField.vue -->
<template>
  <validated-text-field
    :value="value"
    :required="required"
    :input-max-length="50"
    :error="error"
    maxlength="50"
    counter
    dense
    outlined
    hide-details="auto"
    placeholder="例：オープン価格"
    @input="$emit('input', $event)"
    @update:error="$emit('update:error', $event)"
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
    },
    required: {
      type: Boolean,
      default: true,
    },
    /**
     * バリデーションエラーの有無
     * エラーがある場合はtrue，ない場合はfalse
     */
    error: {
      type: Boolean,
      default: false
    },
  },
}
</script>
