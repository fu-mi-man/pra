<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="6" lg="4" xl="3">
      API: {{ message }}
      <v-card class="pa-4" max-width="400" elevation="2">
        <v-file-input
          v-model="file"
          accept=".zip"
          label="ZIPファイルを選択"
          prepend-icon="mdi-file-upload"
          outlined
          dense
        ></v-file-input>
        <v-btn
          class="mt-2"
          color="primary"
          block
          :disabled="!file"
          @click="uploadFile"
        >
          <v-icon left>mdi-cloud-upload</v-icon>
          アップロード
        </v-btn>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data() {
    return {
      file: null,
      message: '',
    }
  },
  async mounted() {
    try {
      const response = await this.$axios.$get('/api/hello')
      this.message = response.message
    } catch (error) {
      alert('Error fetching data:', error)
      this.message = 'Error fetching data'
    }
  },
  methods: {
    async uploadFile() {
      if (!this.file) {
        alert('ファイルを選択してください')
        return
      }

      const formData = new FormData()
      formData.append('imagesZip', this.file)

      try {
        const response = await this.$axios.$post('/api/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
        alert('アップロード成功:' + response.message)
      } catch (error) {
        alert('アップロードエラー:' + error.response.data.message || error.message)
      }
    },
  },
}
</script>
