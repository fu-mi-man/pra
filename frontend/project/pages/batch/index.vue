<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" md="6">
        <v-card class="pa-4">
          <v-btn color="primary" :loading="loading" @click="callBatchApi">
            バッチAPI実行
          </v-btn>
          <v-alert v-if="message" type="success" class="mt-4">
            {{ message }}
          </v-alert>
          <v-alert v-if="error" type="error" class="mt-4">
            {{ error }}
          </v-alert>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      message: '',
      error: '',
      loading: false,
    }
  },
  methods: {
    async callBatchApi() {
      this.loading = true
      this.message = ''
      this.error = ''
      try {
        const response = await this.$axios.$post('/api/batch')
        this.message = response.message || 'バッチAPI呼び出し成功'
      } catch (e) {
        this.error = e.response?.data?.message || e.message || 'API呼び出し失敗'
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
