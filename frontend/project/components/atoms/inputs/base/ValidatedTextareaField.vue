<!-- @/components/atoms/inputs/base/ValidatedTextareaField.vue -->
<template>
  <v-textarea
    v-bind="$attrs"
    :value="value"
    :rules="rules"
    @input="$emit('input', $event)"
    @update:error="$emit('update:error', $event)"
  />
</template>

<script>
/**
 * バリデーション機能付きテキストエリアコンポーネント
 * 必須チェック、最大文字数チェック、特殊文字チェック、全角数字チェックの機能を提供します
 *
 * @example
 * <validated-textarea-field
 *   v-model="inputValue"
 *   :required="true"
 *   :input-max-length="1000"
 *   :disable-special-chars="true"
 * />
 */
export default {
  name: 'ValidatedTextareaField',
  inheritAttrs: false,

  props: {
    /**
     * テキストエリアの入力値
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
    // disableSpecialChars: {
    //   type: Boolean,
    //   default: false
    // },
    /**
     * バリデーションエラーメッセージ
     */
    messages: {
      type: Object,
      default: () => ({
        required: '入力は必須です',
        inputMaxLength: '{inputMaxLength}文字以内で入力してください',
        // specialChars: '特殊文字は使用できません',
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
      // if (this.disableSpecialChars) {
      //   rules.push(v => {
      //     if (!v) return true
      //     return !/[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]+/.test(v) || this.messages.specialChars
      //   })
      // }

      return rules
    }
  }
}
</script>
