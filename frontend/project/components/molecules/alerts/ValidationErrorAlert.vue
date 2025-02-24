<!-- components/molecules/alerts/ValidationErrorAlert.vue -->
<template>
  <v-alert
    v-if="errors.length > 0"
    class="mb-4"
    dense
    type="error"
  >
    <ul class="mb-0 pl-4">
      <li v-for="(error, index) in errors" :key="index">
        {{ error }}
      </li>
    </ul>
  </v-alert>
</template>

<script>
/**
 * バリデーションエラーを表示するための再利用可能なアラートコンポーネント
 */
export default {
  name: 'ValidationErrorAlert',
  props: {
    /** エラーメッセージの配列 */
    errors: {
      type: Array,
      default: () => [],
    },
  },
}
</script>
