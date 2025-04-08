<!-- pages/scroll/index.vue -->
<template>
  <v-container fluid>
    <h2>出展者一覧</h2>

    <!-- テーブルヘッダー -->
    <div class="d-flex align-center py-3 px-6 grey lighten-4 table__header">
      <div class="table__cell table__cell--checkbox">
        <v-checkbox
          v-model="selectAll"
          dense
          hide-details
          @change="toggleSelectAll"
        />
      </div>
      <div class="flex-grow-1 font-weight-bold table__cell table__cell--name">商品名</div>
      <div class="font-weight-bold table__cell table__cell--source">共有元</div>
      <div class="font-weight-bold table__cell table__cell--request">リクエスト</div>
    </div>

    <v-virtual-scroll
      v-if="renderVirtualScroll"
      ref="virtualScroll"
      class="table__body"
      item-height="56"
      :height="'calc(100vh - 240px)'"
      :items="currentPageEnterprises"
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
          <div
            class="flex-grow-1 py-2 px-2 text-subtitle-1 table__cell table__cell--name"
            @click="showProductDetail(item.id)"
          >
            {{ item.name }}
          </div>

          <!-- 共有元 -->
          <div class="table__cell table__cell--source">
            {{ item.source || '未設定' }}
          </div>

          <!-- 承認ボタン -->
          <div class="mr-6">
            <v-btn color="success" @click="approveProduct(item.id)">
              承認する
            </v-btn>
          </div>

          <!-- 否認ボタン -->
          <div>
            <v-btn color="error" @click="rejectProduct(item.id)">
              削除する
            </v-btn>
          </div>
        </div>
      </template>
    </v-virtual-scroll>

    <!-- ページネーション -->
    <div v-if="renderVirtualScroll" class="d-flex flex-column align-center mt-6">
      <div v-if="totalItems > 0" class="text-body-1 grey--text">
        全{{ formattedTotalItems }}件
      </div>
      <v-pagination
        v-model="page"
        :disabled="loading"
        :length="pageCount"
        :total-visible="7"
        class="mb-2"
        @input="changePage"
      />
    </div>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      currentPageEnterprises: [],
      renderVirtualScroll: true, // VirtualScrollの再マウントを制御するフラグ
      selectAll: false,          // 全選択チェックボックスの状態
      selectedItems: [],         // 選択された項目のID配列

      // ローディング
      loading: false,

      // ページネーション
      page: 1,            // 現在のページ番号
      itemsPerPage: 1000, // 1ページあたりのアイテム数
      totalItems: 0,      // 全データ数（サーバーから取得）
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
     * 現在のページ番号が変更されたときに実行される
     * 新しいページのデータを取得するためにfetchEnterprisesメソッドを呼び出す
     * @param {number} newPage - 新しいページ番号
     * @param {number} oldPage - 以前のページ番号
     */
    page() {
      this.fetchEnterprises()
    },
  },

  created() {
    // 初期データ取得
    this.fetchEnterprises()
  },

  methods: {
    // Enterprise一覧データの取得
    async fetchEnterprises() {
      this.loading = true

      try {
        // renderVirtualScrollをfalseにすることでv-ifが再評価され，
        // コンポーネントが再マウントされることでスクロール位置をリセットする
        this.renderVirtualScroll = false
        const response = await this.$axios.$get('/api/enterprises', {
          params: {
            page: this.page,
            per_page: this.itemsPerPage,
          }
        })

        this.currentPageEnterprises = response.data
        this.totalItems = response.total
        this.renderVirtualScroll = true
      } catch (error) {
        // エラー通知を表示
        // this.$store.dispatch('snackbar/setSnackbar', {
        //   color: 'error'
        //   text: 'データの取得に失敗しました'
        // })
      } finally {
        this.loading = false
      }
    },
    /**
     * 全選択の切り替え処理
     */
    toggleSelectAll() {
      if (this.selectAll) {
        // すべての項目を選択
        this.selectedItems = this.currentPageEnterprises.map(item => item.id)
      } else {
        // 選択をクリア
        this.selectedItems = []
      }
    },
    /**
     * 指定されたページ番号へ遷移する
     * 現在と同じページ番号の場合は処理をスキップし，クエリパラメータを使用して画面遷移を行う
     *
     * @param {number} pageNumber - 移動先のページ番号
     */
    changePage(pageNumber) {
      // ページ番号が現在と同じなら何もしない
      if (this.page === pageNumber) return

      // クエリパラメータを使ってページ遷移
      this.$router.push({
        query: {
          ...this.$route.query,
          page: pageNumber
        }
      })
    },
  }
}
</script>

<style lang="scss" scoped>
.table {
  &__header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
  }

  &__body {
    border-top: 1px solid rgba(0, 0, 0, 0.12);
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
  }

  &__row {
    height: 56px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);

    &:hover {
      background-color: rgba(0, 0, 0, 0.04);
    }
  }

  &__cell {
    // 共通のセルスタイル

    // チェックボックス
    &--checkbox {
      width: 40px;
    }

    // 商品名
    &--name {
      // flex-grow: 1; は既にHTMLで指定
    }

    // 共有元
    &--source {
      width: 150px;
    }

    // リクエスト
    &--request {
      width: 240px;
    }
  }

  &__checkbox {
    margin: 0;
    padding: 0;
  }

  &__action {
    // ボタンを含むセル
  }
}
</style>
