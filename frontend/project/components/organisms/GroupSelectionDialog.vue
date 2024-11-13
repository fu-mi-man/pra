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

          <v-row>
          <v-col cols="6">
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="グループ情報を入力してください"
              single-line
              hide-details
              class="mb-4"
            ></v-text-field>
          </v-col>
        </v-row>

        <v-data-table
          v-model="selected"
          :search="search"
          :custom-filter="customFilter"
          :headers="headers"
          :items="groups"
          :single-select="true"
          :items-per-page="10"
          :footer-props="{
            'items-per-page-options': [10, 20, 50, 100],
            'items-per-page-text': '表示件数'
          }"
          item-key="groupId"
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
      showError: false, // エラーメッセージの表示制御用
      search: '',       // 検索フィルター文字列
    }
  },
  watch: {
    /**
     * ダイアログの表示状態監視
     * ダイアログが開かれたときにグループ情報を取得する
     */
    isOpen(newValue) {
      if (newValue) {
        this.fetchGroups()
      } else {
        // ダイアログが閉じられた時の処理
        this.groups = []  // 例：データをクリア
        this.search = ''  // 例：検索条件をリセット
      }
    }
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
    /**
     * グループ名，備考，人数に対して検索を行う
     * 検索フィールドの入力値が変更された時に発火
     * @param {any} value 現在の列の値（headers[].valueに対応する値）
     * @param {string} search 検索文字列
     * @param {Object} item 検索対象のグループデータ
     */
    customFilter(value, search, item) {
      if (!search) return true

      const searchLower = search.toString().toLowerCase()

      // 最も検索されそうなフィールドから順番にチェック
      return item.groupName.toString().toLowerCase().includes(searchLower) ||
            item.remarks.toString().toLowerCase().includes(searchLower) ||
            item.memberCount.toString().includes(searchLower)
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
