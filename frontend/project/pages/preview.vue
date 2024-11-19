<template>
  <v-container fluid>
    <v-row justify="center">
      <v-col cols="10">
        <v-btn color="primary" @click="print" class="d-print-none">
          <v-icon left>mdi-printer</v-icon>
          印刷
        </v-btn>

        <h1 class="text-h4 text-center mb-4">見積書</h1>

        <!-- 見積もり依頼先名 -->
        <div class="d-flex">
          <span class="estimate__client estimate__client-name">{{ companyName }}</span>
          <div class="">
            <span class="estimate__client-suffix">様</span>
          </div>
        </div>

        <!-- 見積日付と見積番号 / 自社情報 -->
        <div class="text-right justify-end mb-2 text-body-2 estimate-header__info">
          <span class="estimate-header__date">見積日付：{{ estimateDate }}</span>
          <span class="estimate-header__number mb-4">見積番号：{{ estimateNumber }}</span>
          <span class="estimate-header__company">{{ ourCompanyName }}</span>
          <span class="estimate-header__postal">〒{{ ourPostalCode }}</span>
          <span class="estimate-header__address">{{ ourAddress }}</span>
          <span class="estimate-header__tel">TEL: {{ ourTel }}</span>
        </div>

        <!-- 見積金額合計 -->
        <div class="mb-2">
          <div class="total-box pa-3">
            <span class="text-body-2">お見積金額合計（税込）</span>
            <span class="text-body-1 ml-4 font-weight-bold">￥{{ totalAmount.toLocaleString() }} -</span>
          </div>
        </div>

        <!-- 見積明細テーブル -->
        <v-simple-table class="mb-6">
          <template v-slot:default>
            <thead>
              <tr>
                <th>商品名</th>
                <th class="text-right">数量</th>
                <th class="text-right">単価</th>
                <th class="text-right">金額(税別)</th>
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
        <span class="font-weight-bold">備考</span>
        <div class="remarks mb-6">
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
      companyName: '株式会社テスト株式会社テスト株式会社テスト株式会社',
      estimateDate: '2024年11月19日',
      estimateNumber: 'EST-2024-001',
      ourCompanyName: '株式会社テスト株式会社テスト株式会社テスト株式会社',
      ourPostalCode: '123-4567',
      ourAddress: '東京都新宿区サンプル1-2-3',
      ourTel: '03-1234-5678',
      items: [
        { name: '商品A', quantity: 1, price: 1000000000 },
        { name: '商品B', quantity: 2, price: 5000000 },
        { name: '商品C', quantity: 3, price: 3000000 },
      ],
      remarks: '見積もりの備考が入ります。。。。見積もりの備考が入ります。。。。見積もりの備考が入ります。。。。見積もりの備考が入ります。。。。見積もりの備考が入ります。。。。見積もりの備考が入ります。。。。見積もりの備考が入ります。。。。見積もりの備考が入ります。。。。見積もりの備考が入ります。。。。見積もりの備考が入ります。。。。見積もりの備考が入ります。。。。',
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


<style lang="scss">
@media print {
  .estimate {
    &__client {
      border-bottom: 1px solid black;
      font-size: 16px;
      font-weight: bold;
      text-align: left;
      width: 420px;
    }
  }

  .estimate-header {
    &__info {
      display: grid;
    }
  }
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
