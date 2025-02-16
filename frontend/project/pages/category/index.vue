<!-- pages/category/index.vue -->
<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" lg="10" xl="8">
        <div class="py-4 text-h5 text-center">カテゴリ管理</div>
        <v-card>
          <div class="d-flex justify-end px-4 py-2">
            <v-btn class="mr-2" color="primary">
              <span class="d-none d-sm-inline">新規カテゴリ</span>
              <v-icon class="d-sm-none">mdi-plus</v-icon>
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
                :items="documentCategories"
                :loading="loading"
                dense
              >
                <template #[`item.edit`]="{ item }">
                  <v-btn icon small @click="editCategory(item)">
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
                <template #[`item.edit`]="{ item }">
                  <v-btn icon small @click="editCategory(item)">
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
      activeTab: 0,
      loading: false,
      editDialog: false, // 編集ダイアログの表示制御
      deleteDialog: false, // 削除ダイアログの表示制御
      editedItem: {}, // 編集中のアイテム
      editedIndex: -1, // 編集中のアイテムのインデックス
      deleteTargetItem: {}, // 削除用
      defaultItem: {
        id: null,
        name: '',
      },
      snackbar: false,
      snackbarColor: '',
      snackbarText: '',

      headers: [
        {
          text: 'カテゴリID',
          align: 'start',
          value: 'id',
        },
        {
          text: 'カテゴリ名',
          value: 'name',
        },
        {
          text: '編集',
          value: 'edit',
          sortable: false,
          align: 'center',
          width: '80',
        },
        {
          text: '削除',
          value: 'delete',
          sortable: false,
          align: 'center',
          width: '80',
        },
      ],
      documentCategories: [
        {
          id: 1,
          name: '契約書',
        },
        {
          id: 2,
          name: '報告書',
        },
        {
          id: 3,
          name: '議事録',
        },
      ],
      productCategories: [
        {
          id: 1,
          name: '食品',
        },
        {
          id: 2,
          name: '衣類',
        },
        {
          id: 3,
          name: '電化製品',
        },
      ],
    }
  },
  computed: {
    /**
     * 現在選択中のカテゴリタイプを返す
     * @returns {'document' | 'product'} カテゴリタイプ
     */
    currentCategoryType() {
      return this.activeTab === 0 ? 'document' : 'product'
    }
  },
  methods: {
    editCategory(item) {
      // 編集対象のアイテムが配列の何番目にあるかを記憶
      // 後で保存時にこの位置のデータを更新するために使用
      this.editedIndex = this.documentCategories.indexOf(item)

      // Object.assign({}, item) で，itemオブジェクトの浅いコピーを作成
      // 空のオブジェクト {} に item の中身をコピーする
      // これにより、編集中のデータが元のデータに影響を与えないようにする
      this.editedItem = Object.assign({}, item)
      this.editDialog = true // 編集用ダイアログを表示する
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
        this.activeTab === 0 ? this.documentCategories : this.productCategories

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
