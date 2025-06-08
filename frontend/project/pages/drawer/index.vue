<template>
  <v-container>
    <v-btn
      class="mb-4"
      icon
      @click="drawer = true"
    >
      <v-icon>mdi-filter-variant</v-icon>
    </v-btn>

    <v-navigation-drawer
      v-model="drawer"
      :right="right"
      app
      temporary
      :width="400"
      class="d-flex flex-column"
    >
      <!-- ヘッダー部分 -->
      <div class="px-4 drawer__header">
        <div class="d-flex py-3 align-center justify-space-between">
          <div class="text-h6">絞り込み</div>
          <v-btn
            icon
            @click="drawer = false"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </div>
      </div>

      <v-divider />

      <!-- 条件部分 -->
      <v-list class="pa-0 flex-grow-1 overflow-y-auto drawer__content">
        <v-expansion-panels multiple accordion>
          <!-- 商品種別 -->
          <v-expansion-panel>
            <v-expansion-panel-header>
              商品種別
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-select
                v-model="selectedProductType"
                :items="productTypes"
                label="商品種別"
                outlined
                dense
                hide-details
                class="mt-2"
              />
            </v-expansion-panel-content>
          </v-expansion-panel>

          <!-- 公開状態 -->
          <v-expansion-panel>
            <v-expansion-panel-header>
              公開状態
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-select
                v-model="selectedPublishStatus"
                :items="publishStatuses"
                class="mt-2"
                dense
                hide-details
                label="公開状態"
                outlined
              />
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-list>

      <v-divider />

      <!-- 絞り込みボタン -->
      <div class="pa-4 drawer__footer">
        <v-btn
          block
          color="primary"
          @click="applyFilter"
        >
          絞り込む
        </v-btn>
      </div>
    </v-navigation-drawer>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      drawer: false,
      right: false,
      // 商品種別の選択肢
      productTypes: [
        { text: '全て', value: 'all' },
        { text: '登録した商品', value: 'own' },
        { text: '共有された商品', value: 'shared' },
      ],
      selectedProductType: 'all', // 選択された商品種別
      // 公開状態の選択肢
      publishStatuses: [
        { text: '全て', value: 'all' },
        { text: '未公開', value: 'private' },
        { text: '一般公開', value: 'public' },
        { text: '顧客限定公開', value: 'customer' },
        { text: 'グループ限定公開', value: 'group' },
      ],
      selectedPublishStatus: 'all', // 選択された公開状態
    }
  },
  methods: {
    applyFilter() {
      // 絞り込み処理を実装
      console.log('商品種別:', this.selectedProductType)
      console.log('公開状態:', this.selectedPublishStatus)
      // ここに実際の絞り込み処理を追加
    },
  },
}
</script>
<style scoped>
.drawer__header {
  position: sticky;
  top: 0;
  z-index: 2; /* 1だとスクロールが上に来るので2を指定 */
}

.drawer__content {
  flex-grow: 1;
  height: calc(100vh - 130px);
  overflow-y: auto;
}

.drawer__footer {
  bottom: 0;
  position: sticky;
  z-index: 1;
}
</style>
