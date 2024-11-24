<template>
  <v-dialog
    max-width="1000px"
    :value="value"
    @input="$emit('input', $event)"
  >
    <v-card>
      <v-card-title>
        グループ一覧
      </v-card-title>

      <v-card-text class="mb-10" style="height: 400px;">
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

        <!-- ローディング -->
        <div v-if="loading" class="text-center mt-16">
          <v-progress-circular
            :size="70"
            indeterminate
            color="primary"
            class="my-4"
          ></v-progress-circular>
        </div>

        <!-- 顧客グループ情報 -->
        <v-data-table
          v-else
          v-model="selected"
          :search="search"
          :custom-filter="customFilter"
          :headers="headers"
          :items="groups"
          :single-select="true"
          :show-select="true"
          :items-per-page="5"
          :footer-props="{
            'items-per-page-options': [5, 20, 50, 100],
            'items-per-page-text': '表示件数'
          }"
          item-key="groupId"
          class="cursor-pointer"
          @click:row="handleRowClick"
        />
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
    value: {
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
      loading: false,
    }
  },
  watch: {
    /**
     * ダイアログの表示状態を監視し，表示・非表示に応じた処理を実行する
     *
     * @watch value
     * @param {boolean} newValue - 新しいダイアログの表示状態
     * @property {boolean} immediate - コンポーネントの初期化時にも実行する
     *
     * @description
     * - ダイアログが表示される（true）: グループ情報を非同期で取得
     * - ダイアログが非表示になる（false）: 表示データをクリアしてリセット
     *   - groups配列を空にする
     *   - 検索文字列をクリア
     *   - 選択状態をリセット
     */
    value: {
      handler(newValue) {
        if (newValue) {
          this.fetchGroups()
        } else {
          this.groups = []
          this.search = ''
          this.selected = []
        }
      },
      immediate: true,
    }
  },
  methods: {
    async fetchGroups() {
      this.loading = true
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
      } finally {
        this.loading = false
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
    handleRowClick(item) {
      // 既に選択されている行をクリックした場合は選択解除
      if (this.selected.length && this.selected[0].groupId === item.groupId) {
        this.selected = []
      } else {
        // それ以外の場合は選択
        this.selected = [item]
      }
    },
    close() {
      this.$emit('input', false)
      this.selected = []
    },
    onSet() {
      if (this.selected.length > 0) {
        this.$emit('group-selected', this.selected[0])
      }
      this.$emit('input', false)
      this.selected = []
    },
  }
}
</script>
<style scoped>

>>> .v-data-table tbody tr {
  cursor: pointer;
}
</style>
