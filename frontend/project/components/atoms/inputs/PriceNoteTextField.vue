<!-- @/components/atoms/inputs/PriceNoteTextField.vue -->
<template>
  <validated-text-field
    :value="value"
    :input-max-length="inputMaxLength"
    maxlength="30"
    counter
    dense
    outlined
    clearable
    hide-details="auto"
    placeholder="例：期間限定価格"
  />
</template>

<script>
import ValidatedTextField from '@/components/atoms/inputs/base/ValidatedTextField.vue'

export default {
  name: 'PriceNoteTextField',
  components: {
    ValidatedTextField
  },
  props: {
    /** 価格備考の入力値 */
    value: {
      type: String,
      default: ''
    },
    // 入力可能な最大文字数
    inputMaxLength: {
      type: Number,
      default: 30,
    },
  },
}
</script>
