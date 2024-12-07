<!-- components/atoms/inputs/base/ValidatedNumberField.vue -->
<template>
  <v-text-field
    v-bind="$attrs"
    :value="value"
    :rules="rules"
    type="number"
    @input="$emit('input', $event)"
  />
</template>

<script>
export default {
  name: 'ValidatedNumberField',
  inheritAttrs: false,
  props: {
    /**
     * 入力フィールドの値
     */
    value: {
      type: [String, Number],
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
    inputMaxLength: {
      type: Number,
      default: null
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
        negative: 'マイナスの値は入力できません',
        maxlength: '{maxlength}桁以内で入力してください',
      })
    }
  },
  computed: {
    rules() {
      const rules = []

      if (this.required) {
        rules.push(v => {
          if (v === null || v === undefined) return this.messages.required
          const strValue = String(v)
          return !!strValue.trim() || this.messages.required
        })
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

      if (this.inputMaxLength) {
        rules.push(v => !v || String(v).length <= this.inputMaxLength ||
          this.messages.maxlength.replace('{maxlength}', this.inputMaxLength))
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
