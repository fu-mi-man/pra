<!-- pages/category/index.vue -->
<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" lg="10" xl="8">
        <div class="py-4 text-h5 text-center">カテゴリ管理</div>

        <v-alert
          v-if="errorMessage"
          class="mb-4"
          type="error"
        >
          <div class="font-weight-bold">
            {{ errorMessage }}
          </div>
          <ul v-if="validationErrors.length > 0" class="mt-1 mb-0 pl-4">
            <li v-for="(error, index) in validationErrors" :key="index">
              {{ error }}
            </li>
          </ul>
        </v-alert>

        <v-card>
          <div class="d-flex justify-end px-4 py-2">
            <v-btn class="mr-2" color="primary">
              <v-icon>mdi-plus</v-icon>
              <span class="d-none d-sm-inline ml-2">新規カテゴリ</span>
            </v-btn>
            <v-btn color="secondary">
              <v-icon>mdi-sort</v-icon>
              <span class="d-none d-sm-inline ml-2">並び順を変更</span>
            </v-btn>
          </div>

          <v-tabs v-model="activeTab" class="mb-4" centered>
            <v-tab>文書カテゴリ</v-tab>
            <v-tab>商品カテゴリ</v-tab>
          </v-tabs>

          <v-tabs-items v-model="activeTab" class="px-4">
            <!-- 文書カテゴリのタブパネル -->
            <v-tab-item>
              <v-data-table
                :headers="headers"
                :items="catalogCategories"
                :loading="loading"
                :no-data-text="'表示するカテゴリがありません'"
                dense
              >
                <!-- 編集アイコンと削除アイコン -->
                <template #[`item.edit`]="{ item }">
                  <v-btn icon small @click="showEditDialog(item)">
                    <v-icon small>mdi-pencil</v-icon>
                  </v-btn>
                </template>
                <template #[`item.delete`]="{ item }">
                  <v-btn icon small @click="showDeleteDialog(item)">
                    <v-icon small>mdi-delete</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-tab-item>

            <!-- 商品カテゴリのタブパネル -->
            <v-tab-item>
              <v-data-table
                :headers="headers"
                :items="productCategories"
                :loading="loading"
                dense
              >
                <!-- 編集アイコンと削除アイコン -->
                <template #[`item.edit`]="{ item }">
                  <v-btn icon small @click="showEditDialog(item)">
                    <v-icon small>mdi-pencil</v-icon>
                  </v-btn>
                </template>
                <template #[`item.delete`]="{ item }">
                  <v-btn icon small @click="showDeleteDialog(item)">
                    <v-icon small>mdi-delete</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-col>
    </v-row>
    <!-- 編集ダイアログ -->
    <edit-category-dialog
      v-model="editDialog"
      :category-type="currentCategoryType"
      :item="editedItem"
      @edited="handleEditComplete"
    />
    <!-- 削除ダイアログ -->
    <delete-category-dialog
      v-model="deleteDialog"
      :category-type="currentCategoryType"
      :item="deleteTargetItem"
      @deleted="handleDeleteComplete"
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
import EditCategoryDialog from '@/components/organisms/dialogs/EditCategoryDialog.vue'
import DeleteCategoryDialog from '@/components/organisms/dialogs/DeleteCategoryDialog.vue'

export default {
  name: 'CategoryManagement',
  components: {
    EditCategoryDialog,
    DeleteCategoryDialog,
  },

  data() {
    return {
      // 状態管理
      activeTab: 0,
      loading: false,

      // データ表示用
      headers: [
        {text: 'カテゴリID', align: 'start', value: 'id'},
        {text: 'カテゴリ名', value: 'name'},
        {text: '編集', value: 'edit', sortable: false, align: 'center', width: '80'},
        {text: '削除', value: 'delete', sortable: false, align: 'center', width: '80'},
      ],
      catalogCategories: [],
      productCategories: [],

      // 編集用の初期値を明確に定義（propsのバリデーションを防げる）
      editedItem: {
        id: null,
        name: '',
      }, // 編集中のアイテム
      editedIndex: -1, // 編集中のアイテムのインデックス
      deleteTargetItem: {}, // 削除用

      // ダイアログ表示制御
      editDialog: false,
      deleteDialog: false,

      // スナックバー
      snackbar: false,
      snackbarColor: '',
      snackbarText: '',

      // エラーメッセージ
      validationErrors: [],
      errorMessage: '',
    }
  },
  computed: {
    /**
     * 現在選択中のカテゴリタイプを返す
     * @returns {'catalog' | 'product'} カテゴリタイプ
     */
    currentCategoryType() {
      return this.activeTab === 0 ? 'catalog' : 'product'
    }
  },
  async mounted() {
    await this.fetchCategories()
  },
  methods: {
    /**
     * カテゴリ一覧を取得する
     */
    async fetchCategories() {
      this.loading = true
      this.error = null
      this.validationErrors = []

      try {
        // enterpriseIdは適切な方法で取得することを想定
        const enterpriseId = 59665517
        const response = await this.$axios.$get('/api/categories', {
          params: {
            enterprise_id: enterpriseId
          }
        })

        // レスポンスから各カテゴリを設定
        this.catalogCategories = response.catalog || []
        this.productCategories = response.product || []
      } catch (error) {
        if (error.response?.status === 422) {
          this.validationErrors = Object.values(error.response.data.errors).flat()
        }
        // messageをそのまま入れるとスタックトレースが出力される可能性があるので
        this.errorMessage = 'カテゴリの取得に失敗しました'
      } finally {
        this.loading = false
      }
    },
    /**
     * 編集ダイアログを表示する
     * @param {{id: number, name: string}} item - 編集対象のカテゴリアイテム
     */
    showEditDialog(item) {
      // スプレッド構文で浅いコピーを作成
      // 編集中のデータ変更が元のデータに影響を与えないようにする
      // （APIでの保存が完了するまでテーブルの表示を維持するため）
      this.editedItem = { ...item }
      this.editDialog = true
    },
    /**
     * カテゴリ編集完了時の処理
     * 編集ダイアログからの結果を受け取り、成功時はカテゴリリストの更新とメッセージ表示、
     * 失敗時はエラーメッセージの表示を行う
     *
     * @param {Object} params - 編集完了時のパラメータ
     * @param {Object} params.item - 編集されたカテゴリ情報
     * @param {number} params.item.id - カテゴリID
     * @param {string} params.item.name - カテゴリ名
     * @param {boolean} params.success - 処理の成功/失敗
     * @param {string} params.message - 表示するメッセージ
     */
    handleEditComplete({ item, success, message }) {
      if (!success) {
        this.snackbarColor = 'error'
        this.snackbarText = message
        this.snackbar = true
        return
      }

      // カテゴリタイプに応じて更新対象の配列を選択
      const categories =
        this.activeTab === 0 ? this.catalogCategories : this.productCategories

      // 編集対象のインデックスを検索
      const index = categories.findIndex((c) => c.id === item.id)
      if (index > -1) { // 実はそれほど必要なチェックではない
        // 配列の要素を反応的に更新（直接代入の categories[index] = item ではVueが変更を検知できない
        // @param {Array} categories - 更新対象の配列（catalogCategoriesまたはproductCategories）
        // @param {number} index - 更新する配列のインデックス位置
        // @param {Object} item - 新しいカテゴリデータ（APIのレスポンス）
        this.$set(categories, index, item)
      }

      // 成功メッセージを表示
      this.snackbarColor = 'success'
      this.snackbarText = message
      this.snackbar = true
    },
    /**
     * 削除確認ダイアログを表示する
     * @param {{id: number, name: string}} item - 削除対象のカテゴリアイテム
     */
    showDeleteDialog(item) {
      this.deleteTargetItem = item
      this.deleteDialog = true
    },
    handleDeleteComplete({ item, success, message }) {
      if (!success) {
        this.snackbarColor = 'error'
        this.snackbarText = message
        this.snackbar = true
        return
      }
      // カテゴリタイプの判定は親コンポーネントで行う
      const categories =
        this.activeTab === 0 ? this.catalogCategories : this.productCategories

      const index = categories.findIndex((c) => c.id === item.id)
      if (index > -1) {
        categories.splice(index, 1)
      }

      // 成功メッセージを表示
      this.snackbarColor = 'success'
      this.snackbarText = message
      this.snackbar = true
    },
  },
}
</script>
