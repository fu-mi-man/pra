<template>
  <v-row justify="center">
    <v-col cols="12" md="8">
      <v-card>
        <v-card-title>CSV Upload</v-card-title>
        <v-card-text>
          <v-sheet
            class="d-flex flex-column align-center justify-center pa-6"
            color="grey lighten-4"
            height="300"
            outlined
            @dragover.prevent
            @drop.prevent="onDrop"
          >
            <v-icon size="64" color="grey darken-2" class="mb-4"
              >mdi-cloud-upload</v-icon
            >
            <div class="text-center mb-4">
              Drag and drop your CSV file here
            </div>
            <v-btn color="primary" @click="triggerFileInput">
              Add File
            </v-btn>
            <input
              ref="fileInput"
              type="file"
              accept=".csv"
              style="display: none"
              multiple
              @change="onFileChange"
            />
          </v-sheet>

          <v-list v-if="file" class="mt-4">
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-file-document-outline</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>{{ file.name }}</v-list-item-title>
                <v-list-item-subtitle>{{
                  formatFileSize(file.size)
                }}</v-list-item-subtitle>
              </v-list-item-content>
              <v-list-item-action>
                <v-btn icon @click="removeFile">
                  <v-icon>mdi-close</v-icon>
                </v-btn>
              </v-list-item-action>
            </v-list-item>
          </v-list>

          <v-btn
            class="mt-4"
            color="success"
            :disabled="!file"
            @click="uploadFile"
          >
            Upload CSV
          </v-btn>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'UploadPage',
  layout: 'default',
  data() {
    return {
      file: null,
    }
  },
  methods: {
    triggerFileInput() {
      const fileInput = this.$refs.fileInput // refsがfileInputのDOMを取得
      if (fileInput) {
        // `undefined`と`null`の場合を考慮
        fileInput.click() // 意図的に隠しているファイル要素をクリック
      } else {
        // console.error('ファイル入力要素が見つかりません')
      }
    },
    // ファイル選択後に発火
    onFileChange(event) {
      // ファイル数チェック
      const files = event.target.files
      if (files.length === 0) {
        return
      }
      if (files.length >= 2) {
        event.target.value = '' // ファイル入力をリセット
        return
      }

      const file = files[0]
      if (this.isValidCSV(file)) {
        this.validateCSVContent(file)
      } else {
        alert(`File "${file.name}" is not a valid CSV file.`)
      }

      // ファイル入力をリセット
      event.target.value = ''
    },
    validateCSVContent(file) {
      const reader = new FileReader()
      reader.onload = (e) => {
        // ファイル読み込み完了時に、イベントオブジェクト`e`を受け取る
        const content = e.target.result
        const lines = content.split('\n') // ファイルの内容を行ごとに分割
        if (lines.length > 10000) {
          return
        }

        if (!this.isValidHeader(lines[0].split(','))) {
          alert('Invalid CSV header. Expected columns: id, name, email, age')
          return
        }

        for (let i = 1; i < lines.length; i++) {
          const line = lines[i].trim()
          if (line === '') continue // 空行をスキップ

          if (!this.isValidRow(line.split(','))) {
            alert(`Invalid data in row ${i + 1}`)
            return
          }
        }

        // バリデーション成功
        this.processValidFile(file)
      }
      reader.readAsText(file)
    },
    isValidHeader(header) {
      const expectedColumns = ['id', 'name', 'email', 'age']

      // 空の列チェック
      if (header.some((column) => column.trim() === '')) {
        return
      }

      // 列の順序チェック
      for (let i = 0; i < expectedColumns.length; i++) {
        if (header[i] !== expectedColumns[i]) {
          return false // 期待される列と異なる順序の列がある場合はエラー
        }
      }

      return true // すべてのチェックを通過した場合は正しいヘッダー
    },
    isValidRow(row) {
      if (row.length !== 4) return false
      const [id, name, email, age] = row
      return (
        /^\d+$/.test(id.trim()) &&
        name.trim() !== '' &&
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim()) &&
        /^\d+$/.test(age.trim())
      )
    },
    processValidFile(file) {
      this.file = file // ファイルをコンポーネントの状態に保存
    },
    onDrop(e) {
      const droppedFiles = e.dataTransfer.files
      if (droppedFiles.length > 0) {
        const file = droppedFiles[0]
        if (this.isValidCSV(file)) {
          this.validateCSVContent(file)
        } else {
          alert('Please drop a valid CSV file.')
        }
      }
    },
    isValidCSV(file) {
      const validMimeTypes = [
        'text/csv',
        'application/vnd.ms-excel',
        'application/csv',
      ]
      const isValidMimeType = validMimeTypes.includes(file.type)
      const isValidExtension = file.name.toLowerCase().endsWith('.csv')
      return isValidMimeType || isValidExtension
    },
    uploadFile() {
      if (this.file) {
        // ここで実際のアップロード処理を実装します
        // アップロード成功時の処理例
        this.$emit('upload-success', this.file.name)
      }
    },
    removeFile() {
      this.file = null
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
