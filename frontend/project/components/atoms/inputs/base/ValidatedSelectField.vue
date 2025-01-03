<!-- @/components/atoms/inputs/base/ValidatedSelectField.vue -->
<template>
  <v-select
    v-bind="$attrs"
    :value="value"
    :rules="rules"
    @input="$emit('input', $event)"
    @update:error="$emit('update:error', $event)"
  />
</template>

<script>
/**
 * バリデーション機能付きセレクトフィールドコンポーネント
 * 必須チェックの機能を提供します
 *
 * @example
 * <validated-select-field
 *   v-model="selectedValue"
 *   :items="items"
 *   :required="true"
 * />
 */
export default {
  name: 'ValidatedSelectField',
  inheritAttrs: false,

  props: {
    /**
     * セレクトフィールドの選択値
     * v-modelと組み合わせて使用
     */
    value: {
      type: [String, Number, Object, Array],
      default: null
    },
    /** 必須選択チェックを有効にするフラグ */
    required: {
      type: Boolean,
      default: false
    },
    /** バリデーションエラーメッセージ */
    messages: {
      type: Object,
      default: () => ({
        required: '選択は必須です'
      })
    },
  },

  computed: {
    /**
     * バリデーションルールを生成
     * @returns {Array<Function>} バリデーション関数の配列
     */
    rules() {
      const rules = []

      // 必須チェック
      if (this.required) {
        rules.push(v => {
          if (Array.isArray(v)) {
            return v.length > 0 || this.messages.required
          }
          return !!v || this.messages.required
        })
      }

      return rules
    }
  }
}
</script>
