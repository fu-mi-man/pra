<!-- pages/index.vue -->
<template>
  <v-container>
    <v-radio-group v-model="selectedOption">
      <v-radio
        label="オプション1"
        :value="1"
      ></v-radio>
      <v-radio
        label="オプション2"
        :value="2"
      ></v-radio>
      <v-radio
        label="オプション3"
        :value="3"
      ></v-radio>
    </v-radio-group>

    <template v-if="selectedOption === 3">
      <v-card-title>共有元出展者一覧</v-card-title>
      <v-alert
          v-if="errorMessage"
          type="error"
          dense
          class="mb-4"
        >
        {{ errorMessage }}
      </v-alert>
      <v-row>
        <v-col cols="6">
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="共有元出展者名を入力してください"
            single-line
            hide-details
            class="mb-4"
          />
        </v-col>
      </v-row>
      <!-- ローディング中の表示 -->
      <div v-if="loading" class="d-flex justify-center align-center py-4">
        <v-progress-circular
          :size="70"
          indeterminate
          color="primary"
          class="my-4"
        />
      </div>
      <v-data-table
        v-else
        v-model="selected"
        class="mt-4"
        :headers="headers"
        :items="sharedEnterprises"
        item-key="id"
        :search="search"
        :custom-filter="customFilter"
        :page="page"
        :items-per-page="itemsPerPage"
        hide-default-footer
        single-select
        show-select
        no-data-text="表示するアイテムがありません"
        no-results-text="検索条件に一致する出展者は見つかりませんでした"
        @click:row="handleRowClick"
        @update:page="handlePageChange"
        @page-count="handlePageCountChange"
      />
      <!-- ページネーション -->
      <div class="text-center pt-4">
        <v-pagination
          v-if="showPagination"
          v-model="page"
          :length="pageCount"
          :total-visible="7"
        />
        <!-- <v-select
          v-model="itemsPerPage"
          :items="[5, 25, 50]"
          label="表示件数"
          class="mt-4"
          style="max-width: 150px; margin: auto;"
        /> -->
      </div>
    </template>

    <v-row justify="center" class="mt-4">
      <v-btn
        color="primary"
        :disabled="!selected.length"
        @click="downloadCsv"
      >
        ダウンロード
      </v-btn>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      selectedOption: 1, // 初期値
      selected: [],
      headers: [
        {
          text: '共有元出展者名',
          value: 'enterpriseName',
          align: 'start',
        },
      ],
      search: '', // 検索用の文字列
      sharedEnterprises: [],
      loading: false,

      // ページネーション用のプロパティ
      page: 1,        // 現在のページ番号（1から開始）
      pageCount: 0,   // 総ページ数（データ取得前は0，データ取得後にv-data-tableが自動計算して設定）
      itemsPerPage: 5 // 1ページあたりの表示件数
    }
  },
  computed: {
    /**
     * ページネーションの表示/非表示を制御する
     */
    showPagination() {
      // 表示が0件の時とローディング時はページネーションを非表示
      return this.sharedEnterprises.length > 0 && !this.loading
    }
  },
  watch: {
    // selectedOptionの値を監視
    selectedOption: {
      handler(newValue) {
        switch (newValue) {
          case 1:
            break
          case 2:
            this.exhibitors = []
            this.selected = []
            break
          case 3:
            this.fetchExhibitors()
            break
          default:
            this.exhibitors = []
            this.selected = []
        }
      },
      immediate: true, // コンポーネントの初期化時にも実行
    },
  },
  methods: {
    async fetchExhibitors() {
      // すでにデータがある場合は何もしない
      if (this.sharedEnterprises.length > 0) return

      this.errorMessage = '' // エラーをリセット
      this.loading = true // ローディング開始
      try {
        // 実際のAPIエンドポイントに置き換えてください
        const response = await fetch('http://localhost:80/api/groups')
        const data = await response.json()
        // モックデータ
        // 実際のAPIレスポンスを模倣するため、Promiseでラップ
        // const data = await Promise.resolve([
        //   { id: 1, groupName: '出展者A' },
        //   { id: 2, groupName: '出展者B' },
        //   { id: 3, groupName: '出展者C' },
        // ])
        this.sharedEnterprises = data.map(item => ({
          id: item.id,
          enterpriseName: item.groupName,
        }))
      } catch (error) {
        this.errorMessage = 'APIの取得に失敗しました'
      } finally {
        this.loading = false // ローディング終了
      }
    },
    /**
     * 共有元出展者名に対して検索を行うカスタムフィルター
     * @param {any} value 現在の列に表示されているデータ
     * @param {string} search ユーザが入力した検索文字列
     * @param {Object} item テーブル内の1行分のデータ
     * @returns {boolean} 検索条件に一致するかどうか
     */
    customFilter(_, search, item) {
      // 検索文字列が空の場合、すべての行を表示
      if (search === '' || search === null || search === undefined) return true

      const searchLower = search.toString().toLowerCase()

      return item.enterpriseName.toString().toLowerCase().includes(searchLower)
    },
    downloadCsv() {
      if (!this.selected.length) return

      const selectedExhibitor = this.selected[0]
      const csvData = this.convertToCsv(selectedExhibitor.data)

      const blob = new Blob([csvData], { type: 'text/csv' })
      const url = window.URL.createObjectURL(blob)
      const link = document.createElement('a')
      link.href = url
      link.setAttribute('download', `exhibitor_data_${selectedExhibitor.id}.csv`)
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
    },
    convertToCsv(data) {
      return data.join('\n')
    },
    /**
     * データテーブルの行クリック時のハンドラー
     * 行をクリックすると選択/選択解除を切り替える
     * @param {Object} item - クリックされた行のデータ
     */
    handleRowClick(item) {
      // 既に選択されている行をクリックした場合、選択を解除する
      if (this.selected.length && this.selected[0].id === item.id) {
        this.selected = []
      } else {
        this.selected = [item]
      }
    },
    /**
     * ページ番号が変更された時の処理
     * :page.sync="page" この書き方でもOK
     * @param {number} newPage - 新しいページ番号
     */
    handlePageChange(newPage) {
      this.page = newPage
    },
    /**
     * 総ページ数が変更された時のハンドラー
     * @page-count="pageCount = $event" この書き方でもOK
     * @param {number} totalPages - 計算された総ページ数
     */
    handlePageCountChange(totalPages) {
      this.pageCount = totalPages
    },
  }
}
</script>
<style scoped>
/* テーブル行にマウスホバー時にカーソルを設定 */
::v-deep(.v-data-table tbody tr:hover) {
  cursor: pointer;
}

/* コンテンツエリアのスタイル */
/* .group-dialog__content {
  height: 440px;
  overflow-y: auto;
  position: relative;
} */
</style>
