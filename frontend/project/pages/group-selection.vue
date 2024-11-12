<!-- pages/group-selection.vue -->
<template>
  <v-container>
    <v-btn
      color="primary"
      @click="openDialog"
    >
      ダイアログを開く
    </v-btn>
    <group-selection-dialog
      :is-open="isOpen"
      @close="isOpen = false"
    />
  </v-container>
</template>

<script>
import GroupSelectionDialog from '~/components/organisms/GroupSelectionDialog'

export default {
  components: {
    GroupSelectionDialog
  },
  data() {
    return {
      isOpen: false // ダイアログの開閉状態を管理するブール値
    }
  },
  methods: {
    openDialog() {
      this.isOpen = true
    }
  }
}
</script>
