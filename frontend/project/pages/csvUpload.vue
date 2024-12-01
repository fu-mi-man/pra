<!-- pages/csv-upload.vue -->
<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" md="8">
        <v-card>
          <v-card-title class="headline">
            CSVファイルアップロード
          </v-card-title>

          <v-card-text>
            <v-file-input
              v-model="file"
              accept=".csv"
              label="CSVファイルを選択"
              prepend-icon="mdi-file-upload"
              show-size
              truncate-length="25"
              @change="handleFileChange"
            />

            <v-alert
              v-if="errorMessage"
              type="errorMessage"
              dense
              text
            >
              {{ errorMessage }}
            </v-alert>

            <!-- プレビュー表示 -->

            <v-alert
              v-if="success"
              type="success"
              dense
              text
            >
              ファイルのアップロードが完了しました。
            </v-alert>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              :disabled="!file"
              :loading="loading"
              @click="uploadFile"
            >
              アップロード
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      file: null,
      errorMessage: '',
      success: false,
      loading: false
    }
  },

  methods: {
    handleFileChange(file) {
      this.resetStatus()
      if (!file) return

      if (!this.validateFile(file)) return true
    },
    handleError(errorMessage) {
      this.errorMessage = errorMessage.message
      this.file = null
    },
    resetStatus() {
      this.errorMessage = ''
      this.success = false
    },
    validateFile(file) {
      if (!file.name.endsWith('.csv')) {
        this.errorMessage = 'CSVファイルのみアップロード可能です。'
        this.file = null
        return false
      }

      const maxFileSize = 5 * 1024 * 1024 // 5MB
      if (file.size > maxFileSize) {
        this.errorMessage = 'ファイルサイズは5MB以下にしてください。'
        this.file = null
        return false
      }

      return true
    },
    async uploadFile() {
      if (!this.file) return

      this.loading = true
      this.errorMessage = ''
      this.success = false

      try {
        // FormDataの作成
        const formData = new FormData()
        formData.append('file', this.file)

        // APIエンドポイントへのアップロード処理
        const response = await this.$axios.post('/api/upload-csv', formData)
        console.log(response);

        // アップロード成功時の処理
        this.success = true
        this.file = null

      } catch (error) {
        this.errorMessage = 'アップロード中にエラーが発生しました。'
        console.error('Upload error:', error)
      } finally {
        this.loading = false
      }
    },
  }
}
</script>
