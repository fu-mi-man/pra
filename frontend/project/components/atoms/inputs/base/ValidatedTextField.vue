<!-- @/components/atoms/inputs/base/ValidatedTextField.vue -->
<template>
  <v-text-field
    v-bind="$attrs"
    :value="value"
    :rules="rules"
    type="text"
    @input="$emit('input', $event)"
  />
</template>

<script>
/**
 * バリデーション機能付きテキストフィールドコンポーネント
 * 必須チェック、最大文字数チェック、特殊文字チェック、全角数字チェックの機能を提供します
 *
 * @example
 * <validated-text-field
 *   v-model="inputValue"
 *   :required="true"
 *   :input-max-length="30"
 *   :disable-special-chars="true"
 * />
 */
export default {
  name: 'ValidatedTextField',
  inheritAttrs: false, // 親から渡された属性がroot要素に自動適用されるのを防ぐ

  props: {
    /**
     * テキストフィールドの入力値
     * v-modelと組み合わせて使用
     */
    value: {
      type: String,
      default: ''
    },
    /** 必須入力チェックを有効にするフラグ */
    required: {
      type: Boolean,
      default: false
    },
    /** 最大文字数 */
    inputMaxLength: {
      type: Number,
      default: null
    },
    /**
     * 特殊文字の入力を禁止するフラグ
     * trueの場合、!@#$%^&*()等の特殊文字の入力を許容しません
     */
    disableSpecialChars: {
      type: Boolean,
      default: false
    },
    /**
     * 全角数字の入力を禁止するフラグ
     * trueの場合、１２３等の全角数字の入力を許容しません
     */
    disableFullWidthNum: {
      type: Boolean,
      default: false
    },
    /**
     * バリデーションエラーメッセージ
     */
    messages: {
      type: Object,
      default: () => ({
        required: '入力は必須です',
        inputMaxLength: '{inputMaxLength}文字以内で入力してください',
        specialChars: '特殊文字は使用できません',
        fullWidthNum: '全角数字は使用できません'
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
        rules.push(v => !!v?.trim() || this.messages.required)
      }

      // 最大文字数チェック
      if (this.inputMaxLength) {
        rules.push(v => {
          if (!v) return true
          return v.trim().length <= this.inputMaxLength ||
            this.messages.inputMaxLength.replace('{inputMaxLength}', this.inputMaxLength)
        })
      }

      // 特殊文字チェック
      if (this.disableSpecialChars) {
        rules.push(v => {
          if (!v) return true
          return !/[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]+/.test(v) || this.messages.specialChars
        })
      }

      // 全角数字チェック
      if (this.disableFullWidthNum) {
        rules.push(v => {
          if (!v) return true
          return !/[０-９]/.test(v) || this.messages.fullWidthNum
        })
      }

      return rules
    }
  }
}
</script>
