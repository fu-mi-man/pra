<!-- components/atoms/inputs/PriceTextField.vue -->
<template>
  <validated-number-field
    :value="value"
    :required="required"
    :min="min"
    :input-max-length="inputMaxLength"
    :error="error"
    dense
    outlined
    suffix="円"
    clearable
    hide-spin-buttons
    hide-details="auto"
    @input="$emit('input', $event)"
    @update:error="$emit('update:error', $event)"
  />
</template>

<script>
import ValidatedNumberField from '@/components/atoms/inputs/base/ValidatedNumberField.vue'

/**
 * 価格入力用のテキストフィールドコンポーネント
 * 金額の入力に特化した機能を提供します
 *
 * @example 使用例
 * - 商品登録フォーム
 * - 価格設定画面
 * - 見積作成画面
 */
export default {
  name: 'PriceTextField',
  components: {
    ValidatedNumberField
  },
  props: {
    // 入力値
    value: {
      type: [String, Number],
      default: ''
    },
     // 必須
    required: {
      type: Boolean,
      default: true,
    },
     // 最小値（0は許容しない）
    min: {
      type: Number,
      default: 1,
    },
    // 入力可能な最大桁数（1億桁まで）
    inputMaxLength: {
      type: Number,
      default: 9,
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
