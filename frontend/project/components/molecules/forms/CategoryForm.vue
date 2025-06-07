<!-- components/molecules/forms/CategoryForm.vue -->
<template>
  <v-form
    ref="form"
    @submit.prevent
  >
    <validated-text-field
      v-model="localValue.name"
      label="カテゴリ名"
      :required="true"
      :input-max-length="30"
      :error-messages="errorMessages"
      @update:error="handleError"
    />
  </v-form>
</template>

<script>
import ValidatedTextField from '@/components/atoms/inputs/base/ValidatedTextField.vue'


export default {
  name: 'CategoryForm',

  components: {
    ValidatedTextField
  },

  props: {
    // v-modelで受け取る値
    value: {
      type: Object,
      required: true,
      default: () => ({
        name: ''
      })
    }
  },

  data() {
    return {
      localValue: {
        name: ''
      },
      hasError: false
    }
  },

  watch: {
    // propsの値が変更されたらローカルデータを更新
    value: {
      handler(newValue) {
        this.localValue = { ...newValue }
      },
      immediate: true,
      deep: true
    },
    // ローカルデータが変更されたら親コンポーネントに通知
    localValue: {
      handler(newValue) {
        this.$emit('input', { ...newValue })
      },
      deep: true
    }
  },

  methods: {
    /**
     * フォームのバリデーションを実行する
     * @returns {boolean} バリデーション結果
     */
    validate() {
      return this.$refs.form.validate()
    },

    /**
     * フォームをリセットする
     */
    reset() {
      this.$refs.form.reset()
      this.localValue = { name: '' }
    },

    /**
     * バリデーションエラーの状態を処理する
     * @param {boolean} error - エラーの有無
     */
    handleError(error) {
      this.hasError = error
      this.$emit('update:error', error)
    }
  }
}
</script>
