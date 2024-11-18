<template>
  <v-container fluid>
    <v-row justify="center">
      <v-col cols="10">
        <v-btn color="primary" @click="print" class="no-print">
          <v-icon left>mdi-printer</v-icon>
          印刷
        </v-btn>

        <h1 class="text-h4 text-center mb-4">御見積書</h1>

        <div class="company-name mb-4">
          {{ companyName }}様
          <div class="underline"></div>
        </div>

        <!-- 見積日付と見積番号 -->
        <div class="d-flex justify-end mb-6">
          <div class="text-right">
            <p>見積日：{{ estimateDate }}</p>
            <p>見積番号：{{ estimateNumber }}</p>
          </div>
        </div>

        <!-- 自社情報 -->
        <div class="company-info text-right mb-6">
          <p>{{ ourCompanyName }}</p>
          <p>〒{{ ourPostalCode }}</p>
          <p>{{ ourAddress }}</p>
          <p>TEL: {{ ourTel }}</p>
          <p>FAX: {{ ourFax }}</p>
        </div>

        <!-- 見積金額合計 -->
        <div class="total-amount mb-6">
          <div class="total-box pa-4">
            <span class="text-h6">御見積金額合計（税込）</span>
            <span class="text-h5 ml-4">￥{{ totalAmount.toLocaleString() }}―</span>
          </div>
        </div>

        <!-- 見積明細テーブル -->
        <v-simple-table class="mb-6">
          <template v-slot:default>
            <thead>
              <tr>
                <th>項目</th>
                <th class="text-right">数量</th>
                <th class="text-right">単価</th>
                <th class="text-right">金額</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in items" :key="index">
                <td>{{ item.name }}</td>
                <td class="text-right">{{ item.quantity }}</td>
                <td class="text-right">￥{{ item.price.toLocaleString() }}</td>
                <td class="text-right">￥{{ (item.quantity * item.price).toLocaleString() }}</td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>

        <!-- 小計・消費税・合計 -->
        <div class="d-flex flex-column align-end mb-6">
          <div class="subtotal-box">
            <p>小計：￥{{ subtotal.toLocaleString() }}</p>
            <p>消費税（10%）：￥{{ tax.toLocaleString() }}</p>
            <p class="font-weight-bold">合計：￥{{ totalAmount.toLocaleString() }}</p>
          </div>
        </div>

        <!-- 備考 -->
        <div class="remarks mb-6">
          <p class="font-weight-bold">備考</p>
          <p>{{ remarks }}</p>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      companyName: '株式会社サンプル',
      estimateDate: '2024年11月19日',
      estimateNumber: 'EST-2024-001',
      ourCompanyName: '株式会社テスト',
      ourPostalCode: '123-4567',
      ourAddress: '東京都新宿区サンプル1-2-3',
      ourTel: '03-1234-5678',
      ourFax: '03-1234-5679',
      items: [
        { name: '商品A', quantity: 1, price: 100000 },
        { name: '商品B', quantity: 2, price: 50000 },
        { name: '商品C', quantity: 3, price: 30000 },
      ],
      remarks: '有効期限：発行日より30日間\n支払条件：月末締め翌月末払い',
    }
  },
  computed: {
    subtotal() {
      return this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0)
    },
    tax() {
      return Math.floor(this.subtotal * 0.1)
    },
    totalAmount() {
      return this.subtotal + this.tax
    }
  },
  methods: {
    print() {
      window.print()
    }
  }
}
</script>

<style>
@media print {
  .no-print {
    display: none !important;
  }
  .v-container {
    max-width: 100% !important;
    width: 100% !important;
  }
}

.company-name {
  width: 300px;
  text-align: left;
  font-size: 16px;
}

.underline {
  border-bottom: 1px solid black;
  margin-top: 4px;
}

.total-box {
  border: 2px solid #000;
  display: inline-block;
}

.subtotal-box {
  width: 300px;
}

.remarks {
  border: 1px solid #ccc;
  padding: 16px;
}
</style>
