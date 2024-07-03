<template>
  <v-card>
    <v-card-title>{{ title }}</v-card-title>
    <v-card-text>
      <v-sheet
        class="file-drop-area d-flex flex-column align-center justify-center pa-6"
        color="grey lighten-4"
        height="300"
        outlined
        @dragenter.prevent="onDragEnter"
        @dragleave.prevent="onDragLeave"
        @dragover.prevent
        @drop.prevent="onDrop"
      >
        <template v-if="!isDragging">
          <v-icon size="64" color="grey darken-2" class="mb-4">
            {{ icon }}
          </v-icon>
          <div class="text-center mb-4">
            Drag and drop your {{ fileType }} file here
          </div>
          <v-btn color="primary" @click="triggerFileInput"> Add File </v-btn>
        </template>
        <template v-else>
          <div class="text-center">Drop your file here</div>
        </template>
        <input
          ref="fileInput"
          type="file"
          :accept="accept"
          :multiple="multiple"
          style="display: none"
          @input="onFileInput"
        />
      </v-sheet>

      <v-list v-if="files.length > 0" class="mt-4">
        <v-list-item v-for="(file, index) in files" :key="index">
          <v-list-item-icon>
            <v-icon>{{ fileIcon }}</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ file.name }}</v-list-item-title>
            <v-list-item-subtitle>{{
              formatFileSize(file.size)
            }}</v-list-item-subtitle>
          </v-list-item-content>
          <v-list-item-action>
            <v-btn icon @click="removeFile(index)">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-list-item-action>
        </v-list-item>
      </v-list>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'FileUploader',
  props: {
    title: {
      type: String,
      required: true,
    },
    fileType: {
      type: String,
      required: true,
    },
    accept: {
      type: String,
      required: true,
    },
    icon: {
      type: String,
      default: 'mdi-cloud-upload',
    },
    fileIcon: {
      type: String,
      default: 'mdi-file-outline',
    },
    multiple: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      files: [],
      isDragging: false,
    }
  },
  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click()
    },
    onFileInput(event) {
      const inputFiles = [...event.target.files] // アップロードファイルは、常に配列として扱う
      this.processFiles(inputFiles)
    },
    onDragEnter(e) {
      this.isDragging = true
    },
    onDragLeave(e) {
      this.isDragging = false
    },
    onDrop(e) {
      this.isDragging = false
      const droppedFiles = [...e.dataTransfer.files] // アップロードファイルは、常に配列として扱う
      this.processFiles(droppedFiles)
    },
    processFiles(files) {
      if (this.multiple) {
        this.files = [...this.files, ...files]
      } else {
        // this.files = [files[0]] と同じ意味
        const [firstFile] = files
        this.files = [firstFile]
      }
      this.$emit('files-selected', this.files)
    },
    removeFile(index) {
      this.files.splice(index, 1)
      this.$emit('file-removed')
      this.$refs.fileInput.value = ''
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

<style scoped>
.file-drop-area {
  transition: all 0.3s ease;
}
.file-drop-area:hover {
  background-color: #e0e0e0 !important;
}
</style>
