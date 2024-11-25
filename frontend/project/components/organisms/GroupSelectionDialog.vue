<template>
  <v-dialog
    max-width="1000px"
    :value="value"
    @input="$emit('input', $event)"
    @click:outside="close"
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
        <v-alert
          v-if="showSelectionError"
          type="error"
          dense
          class="mb-4"
        >
          グループを1件選択してください
        </v-alert>

        <!-- 検索フィルター -->
        <v-row>
          <v-col cols="6">
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="グループ情報を入力してください"
              single-line
              hide-details
              class="mb-4"
            />
          </v-col>
        </v-row>

        <!-- ローディング -->
        <div v-if="loading" class="text-center mt-16">
          <v-progress-circular
            :size="70"
            indeterminate
            color="primary"
            class="my-4"
          />
        </div>

        <!-- 顧客グループ情報 -->
        <v-data-table
          v-else
          v-model="selected"
          :headers="headers"
          :items="groups"
          :items-per-page="5"
          :search="search"
          :custom-filter="customFilter"
          :single-select="true"
          :show-select="true"
          item-key="groupId"
          :footer-props="{
            'items-per-page-options': [5, 20, 50, 100],
            'items-per-page-text': '表示件数'
          }"
          @click:row="handleRowClick"
        />
        <!-- <v-pagination
          v-model="page"
          :length="'3'"
        ></v-pagination> -->
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
          @click="handleSetGroup"
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
    /**
     * ダイアログの表示状態を制御するプロパティ
     * - 親コンポーネントから`v-model`として渡される値
     * @type {Boolean}
     */
    value: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      headers: [
        {
          text: '顧客グループ名',
          value: 'groupName',
          align: 'start',
        },
        {
          text: '人数',
          value: 'memberCount',
          align: 'start',
        },
      ],
      groups: [],       // 顧客グループデータのリスト
      search: '',       // 検索フィルターの入力値（フィルタリングに使用）
      selected: [],     // データテーブルで選択された行のデータ（v-data-table の選択機能に使用）
      loading: false,

      showError: false, // API取得エラー
      showSelectionError: false, // グループ未選択エラー
    }
  },
  watch: {
    /**
     * ダイアログの表示状態を監視し，表示・非表示に応じた処理を実行する
     * @param {boolean} newValue - ダイアログの開閉状態（true, false）
     * @property {boolean} immediate - コンポーネントの初期化時にも実行する
     */
    value: {
      handler(newValue) {
        if (newValue) {
          this.fetchGroups()
        } else {
          this.resetState()
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
     * @param {any} value 現在の列に表示されているデータ
     * @param {string} search ユーザが入力した検索文字列
     * @param {Object} item テーブル内の1行分のデータ
     */
    customFilter(_, search, item) {
      // 検索文字列が空の場合、すべての行を表示する
      if (search === '' || search === null || search === undefined) return true

      const searchLower = search.toString().toLowerCase()

      // 最も検索されそうなフィールドから順番にチェック
      return item.groupName.toString().toLowerCase().includes(searchLower) ||
            item.memberCount.toString().includes(searchLower)
    },
    /**
     * データテーブルの行クリック時のハンドラー
     * 行をクリックすると選択/選択解除を切り替える
     * @param {Object} item - クリックされた行のデータ
     * @property {string} item.groupId - グループID
     * @property {string} item.groupName - グループ名
     * @property {number} item.memberCount - グループメンバー数
     * @example
     * // itemの形式
     * {
     *   groupId: "group_123",
     *   groupName: "開発チーム",
     *   memberCount: 5
     * }
     */
    handleRowClick(item) {
      // 既に選択されている行をクリックした場合、選択を解除する
      if (this.selected.length && this.selected[0].groupId === item.groupId) {
        this.selected = []
      } else {
        this.selected = [item]
      }
    },
    /**
     * ダイアログを閉じるメソッド
     * @description
     * - キャンセルボタン押下時
     * - ダイアログ外クリック時
     * @emits {boolean} input - ダイアログの表示状態を制御する値（false）
     */
    close() {
      this.closeDialog()
    },
    /**
     * グループ選択を確定し、親コンポーネントに通知するメソッド
     * @emits {Object} group-selected - 選択されたグループオブジェクト
     * @emits {boolean} input - ダイアログの表示状態を制御する値（false）
     */
    handleSetGroup() {
      if (this.selected.length === 0) {
        // 選択しないで設定ボタンを押下した場合
        this.showSelectionError = true
        return
      }
      this.$emit('group-selected', this.selected[0])
      this.closeDialog()
    },
    /**
     * ダイアログの状態をリセットするメソッド
     */
    closeDialog() {
      this.resetState()
      this.$emit('input', false)
    },
    resetState() {
      this.selected = []
      this.showSelectionError = false
      this.groups = []
      this.search = ''
    }
  }
}
</script>
<style scoped>
/* テーブル行にマウスホバー時にカーソルを設定 */
::v-deep(.v-data-table tbody tr:hover) {
  cursor: pointer;
}
</style>
