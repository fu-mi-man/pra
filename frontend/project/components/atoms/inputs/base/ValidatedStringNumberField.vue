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
  name: 'ValidatedStringNumberField',
  inheritAttrs: false,
  props: {
    /**
     * 入力フィールドの値
     */
    value: {
      type: String,
      default: ''
    },
    /**
     * 必須入力を有効にする
     */
    required: {
      type: Boolean,
      default: false
    },
    /**
     * 最小値
     */
    min: {
      type: Number,
      default: null
    },
    /**
     * 最大値
     */
    max: {
      type: Number,
      default: null
    },
    /**
     * マイナス値を無効にする
     */
    disableNegative: {
      type: Boolean,
      default: true
    },
    messages: {
      type: Object,
      default: () => ({
        required: '入力は必須です',
        min: '{min}以上の値を入力してください',
        max: '{max}以下の値を入力してください',
        number: '数値を入力してください',
        negative: 'マイナスの値は入力できません'
      })
    }
  },
  computed: {
    sanitizedValue() {
      return this.value.replace(/\D/g, '')
    },
    rules() {
      const rules = []

      if (this.required) {
        rules.push(v => !!v?.trim() || this.messages.required)
      }

      rules.push(v => {
        if (!v) return true
        return !isNaN(Number(v)) && /^-?\d*$/.test(v) || this.messages.number
      })

      if (this.disableNegative) {
        rules.push(v => {
          if (!v) return true
          return Number(v) >= 0 || this.messages.negative
        })
      }

      if (this.min !== null) {
        rules.push(v => {
          if (!v) return true
          return Number(v) >= this.min || this.messages.min.replace('{min}', this.min)
        })
      }

      if (this.max !== null) {
        rules.push(v => {
          if (!v) return true
          return Number(v) <= this.max || this.messages.max.replace('{max}', this.max)
        })
      }

      return rules
    }
  },
}
</script>
