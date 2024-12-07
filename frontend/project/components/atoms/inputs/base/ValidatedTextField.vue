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
export default {
  name: 'ValidatedTextField',
  inheritAttrs: false, // 親から渡された属性がroot要素に自動適用されるのを防ぐ

  props: {
    /** 入力値 */
    value: {
      type: String,
      default: ''
    },
    /** 必須入力チェックを有効にする */
    required: {
      type: Boolean,
      default: false
    },
    /** 最大文字数 */
    inputMaxLength: {
      type: Number,
      default: null
    },
    disableSpecialChars: {
      type: Boolean,
      default: false
    },
    disableFullWidthNum: {
      type: Boolean,
      default: false
    },
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

      if (this.required) {
        rules.push(v => !!v?.trim() || this.requiredMessage)
      }

      if (this.inputMaxLength) {
        rules.push(v => {
          if (!v) return true
          return v.trim().length <= this.inputMaxLength ||
            this.messages.inputMaxLength.replace('{inputMaxLength}', this.inputMaxLength)
        })
      }

      if (this.disableSpecialChars) {
        rules.push(v => {
          if (!v) return true
          return !/[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]+/.test(v) || this.messages.specialChars
        })
      }

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
