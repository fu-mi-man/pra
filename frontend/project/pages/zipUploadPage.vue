<template>
  <v-row justify="center">
    <v-col cols="12" md="8">
      <file-uploader
        title="CSV Upload"
        file-type="CSV"
        accept=".csv"
        :multiple="false"
        icon="mdi-file-delimited"
        file-icon="mdi-file-document-outline"
        @files-selected="onCSVFilesSelected"
        @file-removed="onCSVFileRemoved"
      />

      <v-divider class="my-6"></v-divider>

      <file-uploader
        title="ZIP Upload"
        file-type="ZIP"
        accept=".zip"
        :multiple="false"
        icon="mdi-zip-box"
        file-icon="mdi-zip-box-outline"
        @files-selected="onZIPFilesSelected"
        @file-removed="onZIPFileRemoved"
      />

      <v-btn
        class="mt-4"
        color="success"
        :disabled="!csvFile || !zipFile"
        @click="uploadFiles"
      >
        Upload Files
      </v-btn>

      <v-card v-if="zipFile" class="mt-4">
        <v-card-title>Uploaded ZIP File</v-card-title>
        <v-card-text>
          <p>Filename: {{ zipFile.name }}</p>
          <p>Size: {{ formatFileSize(zipFile.size) }}</p>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import FileUploader from '@/components/FileUploader.vue'

export default {
  name: 'ZipUploadPage',
  components: {
    FileUploader,
  },
  data() {
    return {
      csvFile: null,
      zipFile: null,
    }
  },
  methods: {
    async onCSVFilesSelected(files) {
      const file = files[0]
      if (!this.hasSingleCSVFile(files)) {
        // Error Messageなどを画面に出す
        this.csvFile = null
      }
      if (!this.validateCSVMimeType(file)) {
        // Error Messageなどを画面に出す
        this.csvFile = null
      }

      if (!this.validateCSVExtension(file)) {
        // Error Messageなどを画面に出す
        this.csvFile = null
      }

      const isValid = await this.validateCSVContent(file)
      if (isValid) {
        this.csvFile = file
      } else {
        console.error('Invalid CSV content')
        this.csvFile = null
      }
    },
    hasSingleCSVFile(file) {
      return file.length === 1
    },
    validateCSVMimeType(file) {
      const validMimeTypes = [
        'text/csv',
        'application/vnd.ms-excel',
        'application/csv',
      ]
      return validMimeTypes.includes(file.type)
    },
    validateCSVExtension(file) {
      return file.name.toLowerCase().endsWith('.csv')
    },
    onCSVFileRemoved() {
      this.csvFile = null
    },
    async validateCSVContent(file) {
      return await true
      // try {
      //   console.log('AA');
      //   const formData = new FormData()
      //   formData.append('file', file)

      //   const response = await this.$axios.post('/api/validate-csv', formData, {
      //     headers: {
      //       'Content-Type': 'multipart/form-data',
      //     },
      //   })

      //   if (response.data.isValid) {
      //     return true
      //   } else {
      //     this.showError(response.data.errorMessage || 'Invalid CSV content')
      //     return false
      //   }
      // } catch (error) {
      //   this.showError(
      //     'Error validating CSV file: ' +
      //       (error.response?.data?.message || error.message)
      //   )
      //   return false
      // }
    },
    onZIPFilesSelected(files) {
      const file = files[0]
      if (!this.hasZipFiles(file)) {
        // Error Messageなどを画面に出す
      }
      if (!this.validateZIPMimeType(file)) {
        // Error Messageなどを画面に出す
      }
      if (!this.validateZIPExtension(file)) {
        // Error Messageなどを画面に出す
      }
    },
    hasSingleZIPFile(file) {
      return file.length === 1
    },
    validateZIPMimeType(file) {
      return file.type === 'application/zip'
    },
    validateZIPExtension(file) {
      return file.name.toLowerCase().endsWith('.zip')
    },
    onZIPFileRemoved() {
      this.zipFile = null
    },
    uploadFiles() {
      if (this.csvFile && this.zipFile) {
        this.parseCSVAndValidateZIP()
      }
    },
    parseCSVAndValidateZIP() {
      const reader = new FileReader()
      reader.onload = (e) => {
        const csv = e.target.result
        const lines = csv.split('\n')
        const headers = lines[0].split(',')
        const imageNameIndex = headers.findIndex(
          (header) => header.trim().toLowerCase() === 'image_name'
        )

        if (imageNameIndex === -1) {
          alert('CSV file does not contain an "image_name" column.')
          return
        }

        // ここでCSVの内容とZIPファイルの検証を行います
        // 現在の実装では、ZIPファイルの内容を検証せずに単純にアップロードします
        this.handleUpload(lines, this.zipFile)
      }
      reader.readAsText(this.csvFile)
    },
    handleUpload(csvLines, zipFile) {
      // console.log('Uploading CSV data:', csvLines)
      // console.log('Uploading ZIP file:', zipFile.name)
      // ここで実際のアップロード処理を実装します
      alert('Files uploaded successfully!')
    },
    formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes'
      const k = 1024
      const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']
      const i = Math.floor(Math.log(bytes) / Math.log(k))
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
    },
  },
}
</script>
