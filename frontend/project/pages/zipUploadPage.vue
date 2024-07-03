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
    validateCSV(file) {
      const validMimeTypes = [
        'text/csv',
        'application/vnd.ms-excel',
        'application/csv',
      ]
      return (
        validMimeTypes.includes(file.type) ||
        file.name.toLowerCase().endsWith('.csv')
      )
    },
    validateZIP(file) {
      return (
        file.type === 'application/zip' ||
        file.name.toLowerCase().endsWith('.zip')
      )
    },
    onCSVFilesSelected(files) {
      if (files.length > 0 && this.validateCSV(files[0])) {
        this.csvFile = files[0]
      } else {
        // alert('Invalid CSV file')
      }
    },
    onZIPFilesSelected(files) {
      if (files.length > 0 && this.validateZIP(files[0])) {
        this.zipFile = files[0]
      } else {
        // alert('Invalid ZIP file')
      }
    },
    onCSVFileRemoved() {
      this.csvFile = null
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
