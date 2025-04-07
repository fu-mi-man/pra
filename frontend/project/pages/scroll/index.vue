<!-- pages/scroll/index.vue -->
<template>
  <v-container fluid>
    <h2>出展者一覧</h2>

    <!-- テーブルヘッダー -->
    <div class="d-flex align-center py-3 px-6 grey lighten-4 table-header">
      <div class="checkbox-column">
        <v-checkbox
          v-model="selectAll"
          dense
          hide-details
          @change="toggleSelectAll"
        ></v-checkbox>
      </div>
      <div class="flex-grow-1 font-weight-bold">商品名</div>
      <div class="font-weight-bold shared-column">共有元</div>
      <div class="font-weight-bold request-column">リクエスト</div>
    </div>

    <v-virtual-scroll
      v-if="renderVirtualScroll"
      :height="'calc(100vh - 240px)'"
      :items="currentPageEnterprises"
      class="table-borders"
      ref="virtualScroll"
      item-height="56"
    >
      <template v-slot:default="{ item }">
        <div
          v-ripple
          class="d-flex align-center px-6 table-row row-hover"
        >
          <!-- チェックボックス -->
          <div class="checkbox-column">
            <v-checkbox
              v-model="selectedItems"
              :value="item.id"
              dense
              hide-details
            ></v-checkbox>
          </div>

          <!-- 商品名 -->
          <div
            class="flex-grow-1 py-2 px-2 text-subtitle-1"
            @click="showProductDetail(item.id)"
          >
            {{ item.name }}
          </div>

          <!-- 共有元 -->
          <div class="shared-column">
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
.table-header {
  border-bottom: 1px solid rgba(0, 0, 0, 0.12);
}

// チェックボックス
.checkbox-column {
  width: 40px;
}

// 商品名
.name-column {
  flex-grow: 1;
}

// 共有元
.shared-column {
  width: 150px;
}

// リクエスト
.request-column {
  width: 240px;
}

// v-virtual-scroll
.table-borders {
  border-top: 1px solid rgba(0, 0, 0, 0.12);
  border-bottom: 1px solid rgba(0, 0, 0, 0.12);
}

.table-row {
  height: 56px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.12);
}

/* ホバー時の効果 */
.row-hover:hover {
  background-color: rgba(0, 0, 0, 0.04);
}
</style>
