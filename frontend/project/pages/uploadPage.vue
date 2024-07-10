<template>
  <v-row justify="center">
    <v-col cols="12" md="8">
      <file-uploader
        title="CSV Upload"
        file-type="CSV"
        accept=".csv"
        icon="mdi-file-delimited"
        file-icon="mdi-file-document-outline"
        :validate-file="validateCSV"
        @file-selected="onCSVFileSelected"
        @file-removed="onFileRemoved"
      />

      <v-divider class="my-6"></v-divider>

      <file-uploader
        title="Image Upload"
        file-type="image"
        accept="image/jpeg, image/png"
        icon="mdi-image"
        file-icon="mdi-file-image-outline"
        :validate-file="validateImage"
        @file-selected="onImageFileSelected"
        @file-removed="onFileRemoved"
      />

      <v-btn
        class="mt-4"
        color="success"
        :disabled="!csvFile || !imageFiles.length"
        @click="uploadFiles"
      >
        Upload Files
      </v-btn>
    </v-col>
  </v-row>
</template>

<script>
import FileUploader from '@/components/FileUploader.vue'

export default {
  name: 'UploadPage',
  components: {
    FileUploader
  },
  data() {
    return {
      csvFile: null,
      imageFiles: [],
    }
  },
  methods: {
    validateCSV(file) {
      const validMimeTypes = [
        'text/csv',
        'application/vnd.ms-excel',
        'application/csv',
      ]
      return validMimeTypes.includes(file.type) || file.name.toLowerCase().endsWith('.csv')
    },
    validateImage(file) {
      return file.type.startsWith('image/')
    },
    onCSVFileSelected(file) {
      this.csvFile = file
    },
    onImageFileSelected(file) {
      this.imageFiles.push(file)
    },
    onFileRemoved() {
      this.csvFile = null
      this.imageFiles = []
    },
    uploadFiles() {
      if (this.csvFile && this.imageFiles.length > 0) {
        this.parseCSVAndValidateImages()
      }
    },
    parseCSVAndValidateImages() {
      const reader = new FileReader()
      reader.onload = (e) => {
        const csv = e.target.result
        const lines = csv.split('\n')
        const headers = lines[0].split(',')
        const imageNameIndex = headers.findIndex(header => header.trim().toLowerCase() === 'image_name')

        if (imageNameIndex === -1) {
          // alert('CSV file does not contain an "image_name" column.')
          return
        }

        const imageNames = lines.slice(1).map(line => {
          const columns = line.split(',')
          return columns[imageNameIndex]?.trim()
        }).filter(Boolean)

        const uploadedImageNames = this.imageFiles.map(file => file.name)
        const missingImages = imageNames.filter(name => !uploadedImageNames.includes(name))

        if (missingImages.length > 0) {
          alert(`Error: The following images are missing: ${missingImages.join(', ')}`)
        } else {
          // console.log('All required images are present. Proceeding with upload...')
          this.handleUpload(lines, this.imageFiles)
        }
      }
      reader.readAsText(this.csvFile)
    },
    handleUpload(csvLines, imageFiles) {
      // console.log('Uploading CSV data:', csvLines)
      // console.log('Uploading image files:', imageFiles)
      // ここで実際のアップロード処理を実装します
    }
  }
}
</script>
