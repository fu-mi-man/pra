<template>
  <v-text-field
    v-bind="$attrs"
    :value="value"
    :rules="rules"
    @input="$emit('input', $event)"
  />
</template>

<script>
export default {
  name: 'ValidatedTextField',
  inheritAttrs: false, // falseに設定することで，v-bind="$attrs"で明示的に属性を渡す必要がある

  props: {
    value: {
      type: String,
      default: ''
    },
    maxlength: {
      type: Number,
      default: null
    },
    disableSpecialChars: {
      type: Boolean,
      default: false
    },
    disableFullWidthNums: {
      type: Boolean,
      default: false
    },
    required: {
      type: Boolean,
      default: false
    }
  },

  computed: {
    rules() {
      const rules = []

      if (this.required) {
        rules.push(v => !!v?.trim() || '入力は必須です')
      }

      if (this.maxlength) {
        rules.push(v => {
          if (!v) return true
          return v.trim().length <= this.maxlength || `${this.maxlength}文字以内で入力してください`
        })
      }

      if (this.disableSpecialChars) {
        rules.push(v => {
          if (!v) return true
          return !/[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]+/.test(v) || '特殊文字は使用できません'
        })
      }

      if (this.disableFullWidthNums) {
        rules.push(v => {
          if (!v) return true
          return !/[０-９]/.test(v) || '全角数字は使用できません'
        })
      }

      return rules
    }
  }
}
</script>
