<template>
  <v-dialog
    max-width="1000px"
    :value="isOpen"
    @input="close"
  >
    <v-card>
      <v-card-title>
        グループ一覧
      </v-card-title>


      <v-card-text>
        <v-alert
          v-if="showError"
          type="error"
          dense
          class="mb-4"
        >
          グループの取得に失敗しました
        </v-alert>
        <v-data-table
          v-model="selected"
          :headers="headers"
          :items="groups"
          item-key="groupId"
          show-select
        ></v-data-table>
      </v-card-text>


      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="grey darken-1"
          text
          @click="close"
        >
          キャンセル
        </v-btn>
        <v-btn
          color="primary"
          @click="onSet"
        >
          設定する
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'GroupSelectionDialog',
  props: {
    isOpen: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      // テーブルのヘッダー定義
      headers: [
        {
          text: 'グループ名',
          value: 'groupName',
          align: 'start',
        },
        {
          text: '人数',
          value: 'memberCount',
          align: 'start',
        },
        {
          text: '備考',
          value: 'remarks',
          align: 'center',
        }
      ],
      groups: [],
      selected: [],     // 選択されたアイテム
      showError: false  // エラーメッセージの表示制御用
    }
  },
  async created() {
    // コンポーネントが作成されたタイミングでデータを取得
    await this.fetchGroups()
  },
  methods: {
    async fetchGroups() {
      try {
        const response = await fetch('http://localhost:80/api/groups')
        const data = await response.json()
        this.groups = data.map(item => ({
          groupId: item.id,
          groupName: item.groupName,
          memberCount: item.memberCount,
          remarks: item.remarks
        }))
        this.showError = false  // 成功したらエラー表示をクリア
      } catch (error) {
        this.showError = true  // エラー表示をON
      }
    },
    close() {
      this.$emit('close')
      this.selected = [] // ダイアログを閉じる時に選択をクリア
    },
    onSet() {
      this.$emit('close')
      // 後で実装：選択したデータを親コンポーネントに渡す
      this.selected = [] // 選択をクリア
    }
  }
}
</script>
