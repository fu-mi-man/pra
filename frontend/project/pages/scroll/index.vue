<!-- pages/scroll/index.vue -->
<template>
  <v-container fluid>
    <h2>共有リクエスト一覧</h2>
    <!-- バリデーションエラーメッセージ -->
    <validation-error-alert :errors="validationErrors" />

    <!-- 検索フォーム -->
    <div class="mb-4 search-form">
      <validated-text-field
        v-model="searchKeyword"
        append-icon="mdi-magnify"
        class="search-input"
        clearable
        dense
        hide-details
        label="キーワード検索"
        outlined
        :loading="loading"
        :disabled="loading"
        @click:append="executeSearch"
        @keydown.enter="executeSearch"
      />
    </div>

    <!-- テーブルヘッダー -->
    <div class="d-flex align-center py-4 px-6 grey lighten-4 table__header">
      <div class="table__cell table__cell--checkbox">
        <v-checkbox
          v-model="selectAll"
          class="table__checkbox"
          dense
          hide-details
          @change="toggleSelectAll"
        />
      </div>
      <div class="text-center font-weight-bold table__cell table__cell--name">商品名</div>
      <div class="text-center font-weight-bold table__cell table__cell--source">共有元</div>
      <div class="text-center font-weight-bold table__cell table__cell--request">リクエスト</div>
    </div>

    <v-virtual-scroll
      v-if="renderVirtualScroll"
      ref="virtualScroll"
      class="table__body"
      item-height="56"
      :height="'calc(100vh - 240px)'"
      :items="currentPageData"
    >
      <template #default="{ item }">
        <div
          v-ripple
          class="d-flex align-center px-6 table__row"
        >
          <!-- チェックボックス -->
          <div class="table__cell table__cell--checkbox">
            <v-checkbox
              v-model="selectedItems"
              class="table__checkbox"
              dense
              hide-details
              :value="item.id"
            />
          </div>

          <!-- 商品名 -->
          <div class="px-2 text-subtitle-1 table__cell table__cell--name">
            <v-tooltip bottom color="warning" max-width="300">
              <template #activator="{ on, attrs }">
                <div
                  class="table__text-truncate"
                  v-bind="attrs"
                  v-on="on"
                  @click="showProductDetail(item.id)"
                >
                  {{ item.name }}
                </div>
              </template>
              {{ item.name }}
            </v-tooltip>
          </div>

          <!-- 共有元 -->
          <div class="text-center table__cell table__cell--source">
            <v-tooltip bottom color="warning" max-width="300">
              <template #activator="{ on, attrs }">
                <div
                  class="table__text-truncate"
                  color="warning"
                  v-bind="attrs"
                  v-on="on"
                >
                  {{ item.source || '共有元は未設定なのでーす' }}
                </div>
              </template>
              {{ item.source || '共有元は未設定なのでーす' }}
            </v-tooltip>
          </div>

          <!-- リクエスト -->
          <div class="text-center table__cell table__cell--request">
            <!-- 承認ボタン -->
            <v-btn class="mr-3" color="success" @click="approveProduct(item.id)">
              承認する
            </v-btn>
            <!-- 削除ボタン -->
            <v-btn color="error" @click="showDeleteDialog(item)">
              削除する
            </v-btn>
          </div>
        </div>
      </template>
    </v-virtual-scroll>

    <!-- ページネーション -->
    <div v-if="renderVirtualScroll" class="d-flex flex-column mt-6 align-center">
      <div v-if="totalItems > 0" class="text-body-1 grey--text">
        全{{ formattedTotalItems }}件
      </div>
      <v-pagination
        v-model="page"
        class="mb-2"
        :disabled="loading"
        :length="pageCount"
        :total-visible="7"
      />
    </div>

    <!-- 削除ダイアログ -->
    <delete-request-dialog
      v-model="deleteDialog"
      :selected-request="selectedRequest"
      @deleted="handleRequestDeleted"
    />

    <!-- 通知用のsnackbar -->
    <v-snackbar
      v-model="snackbar"
      :color="snackbarColor"
      :timeout="3000"
    >
      {{ snackbarText }}
    </v-snackbar>
  </v-container>
</template>

<script>
import ValidatedTextField from '@/components/atoms/inputs/base/ValidatedTextField.vue'
import ValidationErrorAlert from '@/components/molecules/alerts/ValidationErrorAlert.vue'
import DeleteRequestDialog from '@/components/organisms/dialogs/DeleteRequestDialog.vue'

export default {
  components: {
    ValidatedTextField,
    ValidationErrorAlert,
    DeleteRequestDialog,
  },

  data() {
    return {
      searchKeyword: '', // 検索キーワード

      currentPageData: [],
      renderVirtualScroll: true, // VirtualScrollの再マウントを制御するフラグ
      selectAll: false,          // 全選択チェックボックスの状態
      selectedItems: [],         // 選択された項目のID配列

      // ローディング
      loading: false,

      // 削除ダイアログ関連
      deleteDialog: false,
      selectedRequest: null,

      // バリデーションエラー用の配列
      validationErrors: [],

      // ページネーション
      page: 1,            // 現在のページ番号
      itemsPerPage: 1000, // 1ページあたりのアイテム数
      totalItems: 0,      // 全データ数（サーバーから取得）

      // スナックバー関連
      snackbar: false,
      snackbarText: '',
      snackbarColor: 'success',
    }
  },

  computed: {
    /**
     * 総件数を3桁区切りでフォーマットして返す
     * @returns {string} フォーマット済みの総件数
     */
    formattedTotalItems() {
      return this.totalItems.toLocaleString()
    },
    /**
     * 全データ数とページあたりのアイテム数から総ページ数を計算する
     * @returns {number} 総ページ数
     */
    pageCount() {
      return Math.ceil(this.totalItems / this.itemsPerPage)
    },
  },

  watch: {
    /**
     * ページ遷移を監視する（ページネーション押下時に処理される）
     * 新しいページのデータを取得するためにAPIをコールする
     * @param {number} newPage - 新しいページ番号
     * @param {number} oldPage - 以前のページ番号
     */
    page(newPage, oldPage) {
      this.fetchSharedRequest()
    },
  },

  created() {
    // URLからクエリパラメータを読み込む
    // this.restoreStateFromURL()
    // 初期データ取得
    this.fetchSharedRequest()
  },

  methods: {
    /**
     * URLからクエリパラメータを読み込んで状態を復元する
     */
    restoreStateFromURL() {
      const query = this.$route.query

      // ページ番号の復元
      this.page = query.page ? parseInt(query.page, 10) : 1

      // 検索キーワードの復元
      this.searchKeyword = query.search || ''

      // 将来的な絞り込み条件のためのスペース
      // this.filterStatus = query.status || ''
      // this.filterCategory = query.category || ''
      // など
    },
    /**
     * 共有リクエストのデータを取得する
     * ページネーションと検索条件に基づいてAPIからデータを取得し
     * 仮想スクロールを再マウントしてスクロール位置をリセットする
     * @async
     * @returns {Promise<void>} - 取得処理の結果を示すPromise
     */
    async fetchSharedRequest() {
      this.loading = true

      try {
        // renderVirtualScrollをfalseにすることでv-ifが再評価され，
        // コンポーネントが再マウントされることでスクロール位置をリセットする
        this.renderVirtualScroll = false

        // 情報取得
        const response = await this.$axios.$get('/api/enterprises', {
          params: {
            page: this.page,
            per_page: this.itemsPerPage,
            search: this.searchKeyword || undefined, // 検索キーワードがある場合のみ送信
          }
        })

        this.currentPageData = response.data
        this.totalItems = response.total
        this.renderVirtualScroll = true
      } catch (error) {
        if (error.response?.status === 422) {
          this.validationErrors = Object.values(error.response.data.errors).flat()
          return // finallyは呼ばれる
        }
        this.validationErrors = '共有リクエストの取得に失敗しました。'
      } finally {
        this.loading = false
      }
    },
    /**
     * スクロール位置をリセットせずにデータを再取得する
     */
    async fetchEnterprisesWithoutReset() {
      this.loading = true

      try {
        // 情報取得（renderVirtualScrollは変更しない）
        const response = await this.$axios.$get('/api/enterprises', {
          params: {
            page: this.page,
            per_page: this.itemsPerPage,
            search: this.searchKeyword || undefined, // 検索キーワードがある場合のみ送信
          }
        })
        this.currentPageData = response.data
        this.totalItems = response.total

        // ページ内のデータが全て削除された場合に，空のページを表示せず前のページに戻る処理（いいコメント例）
        // 現在のページが空で かつ 1ページ目ではない場合（ダメなコメント例）
        if (this.currentPageData.length === 0 && this.page > 1) {
          // ページを1つ戻し，pageの変更をwatchに検知させる
          this.page--
          return
        }

        // チェックボックスにチェックが入る謎挙動を防ぐために，APIコール後にチェックボックスをリセットする
        this.selectedItems = []
        this.selectAll = false
      } catch (error) {
        if (error.response?.status === 422) {
          this.validationErrors = Object.values(error.response.data.errors).flat()
          return
        }
        this.validationErrors = '共有リクエストの取得に失敗しました'
      } finally {
        this.loading = false
      }
    },
    /**
     * 検索を実行する
     */
    executeSearch() {
      this.page = 1 // 検索後は必ず1ページ目
      this.fetchSharedRequest()

      // 現在のクエリパラメータをコピー
      // const newQuery = { ...this.$route.query }

      // 検索キーワードの処理
      // if (this.searchKeyword) {
      //   // 検索キーワードがある場合は追加
      //   newQuery.search = this.searchKeyword
      // } else {
      //   // 検索キーワードがない場合は削除
      //   delete newQuery.search
      // }

      // 検索実行時は必ずページ1から表示
      // newQuery.page = 1
      // // URLを更新（これにより$route.queryのwatcherが発火してデータが再取得される）
      // this.$router.push({ query: newQuery })

      // 注：データの取得自体は$route.queryのwatcherで自動的に行われるため
      // ここではfetchExhibitorsを呼び出す必要はない

      // URLクエリパラメータに検索キーワードを反映
      // this.$router.push({
      //   query: {
      //     ...this.$route.query,
      //     page: 1,
      //     search: this.searchKeyword || undefined
      //   }
      // })
      // URLの変更でページが再読み込みされるため、ここではfetchEnterprisesを呼ばない
      // this.fetchEnterprises()
    },
    /**
     * 削除ダイアログを開く
     * @param {Object} request - 削除対象のリクエスト
     */
    showDeleteDialog(request) {
      this.selectedRequest = request // ダイアログ内でデータを編集しないので浅いコピーを作成しない
      this.deleteDialog = true
    },
    /**
     * リクエストが削除された後の処理
     * @param {Object} event - 削除イベントの詳細
     * @param {boolean} event.success - 削除が成功したかどうか
     * @param {string} event.message - 表示するメッセージ
     */
    handleRequestDeleted({ success, message }) {
      if (!success) {
        this.showSnackbar(message, 'error')
        return
      }
      // 削除が成功した場合、スクロール位置を維持したままデータを再取得
      this.fetchEnterprisesWithoutReset()
      this.showSnackbar(message)
    },
    /**
     * 全選択の切り替え処理
     */
    toggleSelectAll() {
      if (this.selectAll) {
        // すべての項目を選択
        this.selectedItems = this.currentPageData.map(item => item.id)
      } else {
        // 選択をクリア
        this.selectedItems = []
      }
    },
    /**
     * スナックバーでメッセージを表示する
     * @param {string} message - 表示するメッセージ
     * @param {'success'|'error'} color - スナックバーの色
     */
    showSnackbar(message, color = 'success') {
      this.snackbarText = message
      this.snackbarColor = color
      this.snackbar = true
    },
  }
}
</script>

<style lang="scss" scoped>
// 検索フォーム
.search-form {
  width: 100%;

  @media (min-width: 600px) {
    max-width: 300px;
  }

  // 虫眼鏡アイコンにポインターを適用
  :deep(.v-input__append-inner .v-icon) {
    cursor: pointer;
    // transition: color 0.2s;
  }
  :deep(.v-input__append-inner .v-icon):hover {
    color: red;
  }
}

.table {
  // テーブルヘッダー
  &__header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
  }

  // VirtualScroll
  &__body {
    border-top: 1px solid rgba(0, 0, 0, 0.12);
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
  }

  // VirtualScrollの行
  &__row {
    height: 56px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);

    &:hover {
      background-color: rgba(0, 0, 0, 0.04);
    }
  }

  // 全てのセル
  &__cell {
    // 共通のセルスタイル
    overflow: hidden;

    // チェックボックス
    &--checkbox {
      width: 40px;
      min-width: 40px;
    }

    // 商品名
    &--name {
      width: calc(70% - 280px); // 70%から固定幅セル(チェックボックス+リクエスト)の幅を引く
      min-width: 200px; // 最小幅を確保
      overflow-wrap: break-word; // スペースのない文字列でも必要に応じて折り返す
    }

    // 共有元
    &--source {
      width: calc(30% - 0px); // 30%
      min-width: 120px;
    }

    // リクエスト
    &--request {
      width: 240px;
      min-width: 240px;
    }
  }

  // チェックボックス本体
  &__checkbox {
    margin: 0;
    padding: 0;
  }

    // 2行表示後に省略するテキスト用のElement
  &__text-truncate {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2em;
    max-height: 2.4em; // 1.2em × 2行
  }
}
</style>
