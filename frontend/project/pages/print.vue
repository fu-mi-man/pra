<!-- frontend/project/pages/print.vue -->
<template>
  <v-container>
    <h1 class="text-h5 mb-4">印刷ページ</h1>
    <v-btn
      color="primary"
      @click="handlePrint"
    >
      印刷する
    </v-btn>
  </v-container>
</template>

<script>
export default {
  name: 'IndexPage',
  methods: {
    handlePrint() {
      console.log('印刷ボタンがクリックされました')
    }
  }
}
</script>
