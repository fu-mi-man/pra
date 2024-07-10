<template>
  <v-row justify="center">
    <v-col cols="12" md="8">
      <v-alert v-if="shouldShowError" type="error" dense outlined class="mb-4">
        {{ errorMessage }}
      </v-alert>
      <file-uploader
        ref="csvUploader"
        title="CSV Upload"
        file-type="CSV"
        accept=".csv"
        :multiple="false"
        icon="mdi-file-delimited"
        file-icon="mdi-file-document-outline"
        :should-clear="shouldClearCSV"
        @files-selected="onCSVFilesSelected"
        @file-removed="onCSVFileRemoved"
        @cleared="onCSVUploaderCleared"
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
      errorMessage: '',
      shouldClearCSV: false,
      shouldShowError: false,
    }
  },
  methods: {
    async onCSVFilesSelected(files) {
      this.clearError();
      // CSVファイルを検証する
      const file = files[0]
      if (!this.hasSingleCSVFile(files)) {
        this.showErrorToUser('単一のCSVファイルを選択してください')
        // this.$refs.csvUploader.clearFiles() 単方向データフローの原則に反するため採用しない
        this.shouldClearCSV = true // ウォッチャーを使用する方を採用
        this.csvFile = null
        return
      }
      if (!this.validateCSVMimeType(file)) {
        this.showErrorToUser('無効なファイル形式です。CSVファイルを選択してください') // prettier-ignore
        this.shouldClearCSV = true
        this.csvFile = null
        return
      }
      if (!this.validateCSVExtension(file)) {
        this.showErrorToUser('無効なファイル拡張子です。.csv形式のファイルを選択してください') // prettier-ignore
        this.shouldClearCSV = true
        this.csvFile = null
        return
      }
      if (!this.validateCSVFileSize(file)) {
        this.showErrorToUser('ファイルサイズが大きすぎます。20MB以下のファイルを選択してください') // prettier-ignore
        this.shouldClearCSV = true
        this.csvFile = null
        return
      }

      // CSVデータを検証する
      const isValid = await this.validateCSV(file)
      if (isValid) {
        this.csvFile = file
      } else {
        this.shouldClearCSV = true
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
    validateCSVFileSize(file) {
      const maxSize = 20 * 1024 * 1024 // 20MB
      return file.size <= maxSize
    },
    async validateCSV(file) {
      // アップロードしたCSVファイルを読み込む
      const content = await this.readCSVContent(file)

      // 内容の検証
      if (!this.validateCSVContent(content)) {
        return false
      }

      return true
    },
    async readCSVContent(file) {
      try {
        return await new Promise((resolve, reject) => {
          const reader = new FileReader()
          reader.onload = (e) => resolve(e.target.result)
          reader.onerror = (e) =>
            reject(new Error('ファイルを読み込めませんでした'))
          reader.readAsText(file)
        })
      } catch (error) {
        // console.error('CSVファイルの読み込み中にエラーが発生しました:', error)
      }
    },
    onCSVFileRemoved() {
      this.csvFile = null
    },
    async validateCSVContent(content) {
      // 行数の検証
      const lines = content.split('\n')
      if (!this.isValidCSVLineCount(lines.length)) {
        return false
      }
      // 列数の検証
      if (!this.isValidCSVColumnCount(lines)) {
        return false
      }
      // ヘッダーの検証（2行目は必ずカラム名で確定）
      const headers = lines[1].split(',')
      if (!this.validateCSVHeaders(headers)) {
        return false
      }
      // データ行の検証
      if (!this.validateCSVData(lines, headers)) {
        return false // エラーメッセージは validateCSVData 内で表示される
      }

      return await true
    },
    isValidCSVLineCount(lineCount) {
      const minLines = 3 // ヘッダー2行分 + 少なくとも1つのデータ行
      const maxLines = 10002 // 最大許容行数

      return minLines <= lineCount && lineCount <= maxLines
    },
    isValidCSVColumnCount(lines) {
      const maxColumns = 120

      return lines.every((line) => {
        const columnCount = line.split(',').length
        return columnCount <= maxColumns
      })
    },
    validateCSVHeaders(headers) {
      const trimmedHeaders = headers.map((h) => h.trim()) // ヘッダーをトリムするが、空の要素は保持する
      const requiredHeaders = ['ID', 'Name', 'Email', 'Age'] // 必須ヘッダー
      const allowDuplicates = ['Message'] // 重複を許可するヘッダー

      const missingHeaders = requiredHeaders.filter(
        (required) =>
          !trimmedHeaders
            .map((h) => h.toLowerCase())
            .includes(required.toLowerCase())
      )
      if (missingHeaders.length > 0) {
        this.showErrorToUser(`必須ヘッダーが不足しています: ${missingHeaders.join(', ')}`) // prettier-ignore
        this.shouldClearCSV = true
        return false
      }

      // 重複ヘッダーのチェック（空文字列は無視）
      const duplicates = trimmedHeaders.filter(
        (header, index, self) =>
          header !== '' && // 空文字列でないヘッダーのみを対象
          self.indexOf(header) !== index && // 現在のindexと最初に現れるindexが異なる場合，重複
          !allowDuplicates.includes(header) // 許可された重複は無視
      )
      if (duplicates.length > 0) {
        this.showErrorToUser(
          `ヘッダーが重複しています: ${missingHeaders.join(', ')}`
        )
        this.shouldClearCSV = true
        return false
      }

      return true
    },
    validateCSVData(lines, headers) {
      const idIndex = headers.findIndex((h) => h.trim() === 'ID')
      const nameIndex = headers.findIndex((h) => h.trim() === 'Name')
      const emailIndex = headers.findIndex((h) => h.trim() === 'Email')
      const ageIndex = headers.findIndex((h) => h.trim() === 'Age')

      for (let i = 2; i < lines.length; i++) {
        const values = lines[i].split(',')

        // 各行の列数チェック
        if (values.length !== headers.length) {
          this.showErrorToUser(`行 ${i + 1} の列数が不正です。`)
          this.shouldClearCSV = true
          return false
        }

        // ID のチェック（空でないこと）
        if (!values[idIndex].trim()) {
          this.showErrorToUser(`行 ${i + 1} の ID が空です。`)
          this.shouldClearCSV = true
          return false
        }

        // Name のチェック（空でないこと）
        if (!values[nameIndex].trim()) {
          this.showErrorToUser(`行 ${i + 1} の Name が空です。`)
          this.shouldClearCSV = true
          return false
        }

        // Email のチェック（簡単な形式チェック）
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        if (!emailRegex.test(values[emailIndex].trim())) {
          this.showErrorToUser(`行 ${i + 1} の Email が不正です。`)
          // this.shouldClearCSV = true
          return false
        }

        // Age のチェック（数値であること）
        if (isNaN(parseInt(values[ageIndex]))) {
          this.showErrorToUser(`行 ${i + 1} の Age が数値ではありません。`)
          this.shouldClearCSV = true
          return false
        }
      }

      return true
    },
    onCSVUploaderCleared() {
      this.csvFile = null
      this.shouldClearCSV = false
    },
    clearError() {
      this.shouldShowError = false
      this.errorMessage = ''
    },
    onZIPFilesSelected(files) {
      const file = files[0]
      if (!this.hasZipFiles(file)) {
        this.showErrorToUser('単一のZIPファイルを選択してください')
      }
      if (!this.validateZIPMimeType(file)) {
        this.showErrorToUser('無効なファイル形式です。ZIPファイルを選択してください') // prettier-ignore
      }
      if (!this.validateZIPExtension(file)) {
        this.showErrorToUser('無効なファイル拡張子です。.zip形式のファイルを選択してください') // prettier-ignore
      }
      if (!this.isValidZIPFileSize(file)) {
        this.showErrorToUser('ファイルサイズが大きすぎます。1GB以下のファイルを選択してください') // prettier-ignore
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
    isValidZIPFileSize(file) {
      const maxSize = 1 * 1024 * 1024 * 1024 // 1GB
      return file.size <= maxSize
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
    showErrorToUser(message) {
      this.errorMessage = message
      this.shouldShowError = true
      this.shouldClearCSV = true
    },
  },
}
</script>
