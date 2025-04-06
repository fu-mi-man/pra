<!-- pages/scroll/index.vue -->
<template>
  <v-container fluid>
    <h2>出展者一覧</h2>

    <!-- テーブルヘッダー -->
    <!-- <div class="d-flex align-center py-3 px-4 grey lighten-4 border rounded-t">
      <div class="flex-grow-1 font-weight-bold">商品名</div>
      <div class="text-center" style="width: 160px">操作</div>
    </div> -->

    <v-virtual-scroll
      :height="'calc(100vh - 180px)'"
      :items="currentPageEnterprises"
      class="table-borders"
      item-height="56"
    >
      <template v-slot:default="{ item }">
        <div
          class="d-flex align-center px-6 border-bottom row-hover"
          :class="{ 'grey lighten-5': index % 2 === 0 }"
          style="height: 56px; cursor: default"
          v-ripple
        >
          <!-- 商品名 -->
          <div
            class="flex-grow-1 py-2 px-2 cursor-pointer text-subtitle-1"
            @click="showProductDetail(item.id)"
          >
            {{ item.name }}
          </div>
          <!-- 承認ボタン -->
          <div class="mr-6">
            <v-btn color="success" @click="approveProduct(item.id)">
              承認
            </v-btn>
          </div>
          <!-- 否認ボタン -->
          <div>
            <v-btn color="error" @click="rejectProduct(item.id)">
              否認
            </v-btn>
          </div>
        </div>
      </template>
    </v-virtual-scroll>

    <!-- ページネーション -->
    <div class="d-flex flex-column align-center mt-6">
      <div v-if="totalItems > 0" class="text-body-1 grey--text">
        全{{ formattedTotalItems }}件
      </div>
      <v-pagination
        v-model="page"
        :disabled="loading"
        :length="pageCount"
        :total-visible="7"
        @input="changePage"
        class="mb-2"
      />
    </div>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      currentPageEnterprises: [],

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
    }
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
        // APIからページネーションを含めたリクエスト
        const response = await this.$axios.$get('/api/enterprises', {
          params: {
            page: this.page,
            per_page: this.itemsPerPage,
          }
        })

        // レスポンスの形式に応じて調整（Laravelのデフォルト形式を想定）
        this.currentPageEnterprises = response.data
        this.totalItems = response.total
        // this.lastFetchedPage = this.page // 取得したページを記録
      } catch (error) {
        // エラー通知を表示
        // this.$store.dispatch('snackbar/setSnackbar', {
        //   color: 'error'
        //   text: 'データの取得に失敗しました'
        // })
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.table-borders {
  border-top: 1px solid rgba(0, 0, 0, 0.12);
  border-bottom: 1px solid rgba(0, 0, 0, 0.12);
}

.border-bottom {
  border-bottom: 1px solid rgba(0, 0, 0, 0.12) !important;
}

/* ホバー時の効果 */
.row-hover:hover {
  background-color: rgba(0, 0, 0, 0.04);
}
</style>
