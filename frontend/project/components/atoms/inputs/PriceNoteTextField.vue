<!-- @/components/atoms/inputs/PriceNoteTextField.vue -->
<template>
  <validated-text-field
    :value="value"
    :input-max-length="inputMaxLength"
    :error="error"
    maxlength="30"
    counter
    dense
    outlined
    clearable
    hide-details="auto"
    placeholder="例：期間限定価格"
    @update:error="$emit('update:error', $event)"
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
      default: 2,
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
