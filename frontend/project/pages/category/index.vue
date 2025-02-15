<!-- pages/category/index.vue -->
<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" lg="10" xl="8">
        <div class="py-4 text-h5 text-center">
          カテゴリ管理
        </div>
        <v-card>
          <div class="d-flex justify-end px-4 py-2">
            <v-btn
              class="mr-2"
              color="primary"
            >
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
                  <v-btn
                    icon
                    small
                    @click="editCategory(item)"
                  >
                    <v-icon small>mdi-pencil</v-icon>
                  </v-btn>
                </template>
                <template #[`item.delete`]="{ item }">
                  <v-btn
                    icon
                    small
                    @click="deleteCategory(item)"
                  >
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
                  <v-btn
                    icon
                    small
                    @click="editCategory(item)"
                  >
                    <v-icon small>mdi-pencil</v-icon>
                  </v-btn>
                </template>
                <template #[`item.delete`]="{ item }">
                  <v-btn
                    icon
                    small
                    @click="deleteCategory(item)"
                  >
                    <v-icon small>mdi-delete</v-icon>
                  </v-btn>
                </template>
              </v-data-table>
            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'CategoryManagement',
  data() {
    return {
      activeTab: 0,
      loading: false,
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
  methods: {
    editCategory(item) {
      // 編集処理
      console.log('Edit category:', item)
    },
    deleteCategory(item) {
      // 削除処理
      console.log('Delete category:', item)
    },
  },
}
</script>
