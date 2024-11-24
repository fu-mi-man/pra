<!-- pages/group-selection.vue -->
<template>
  <v-container>
    <v-btn
      color="primary"
      @click="showGroupSelectionDialog = true"
    >
      ダイアログを開く
    </v-btn>
    <group-selection-dialog
      v-model="showGroupSelectionDialog"
      @group-selected="handleGroupSelected"
    />
  </v-container>
</template>

<script>
import GroupSelectionDialog from '@/components/organisms/GroupSelectionDialog'

export default {
  name: 'GroupSelection',
  components: {
    GroupSelectionDialog
  },
  data() {
    return {
      showGroupSelectionDialog: false // ダイアログの開閉状態を管理するブール値
    }
  },
  methods: {
    handleGroupSelected(group) {
      console.log('Selected group:', group)
      // 選択されたグループの処理をここに実装
    }
  }
}
</script>
