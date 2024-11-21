<template>
  <v-container fluid>
    <v-row justify="center">
      <v-col cols="10">
        <v-btn color="primary" @click="print" class="d-print-none">
          <v-icon left>mdi-printer</v-icon>
          印刷
        </v-btn>

        <!-- 見積もりタイトル -->
        <h1 class="mb-4 text-center text-h5 estimate__title">見積書</h1>

        <!-- 見積もり依頼先名 -->
        <div class="d-flex">
          <span class="estimate__client estimate__client-name">{{ companyName }}</span>
          <div class="">
            <span class="estimate__client-suffix">様</span>
          </div>
        </div>

        <!-- 見積日付と見積番号 / 自社情報 -->
        <div class="mb-2 justify-end text-right estimate-header__info">
          <span class="estimate-header__date">見積日付：{{ estimateDate }}</span>
          <span class="estimate-header__number mb-4">見積番号：{{ estimateNumber }}</span>
          <span class="estimate-header__company">{{ ourCompanyName }}</span>
          <span class="estimate-header__address">〒{{ ourPostalCode }}  {{ ourAddress }}</span>
          <span class="estimate-header__tel">TEL: {{ ourTel }}</span>
        </div>

        <!-- 見積金額合計 -->
        <div class="mb-5 estimate-total__wrapper">
          <div class="pa-2 estimate-total__box">
            <span class="text-body-2 estimate-total__label">お見積金額合計（税込）</span>
            <span class="ml-4 text-body-1 font-weight-bold estimate-total__amount">
              ￥{{ totalAmount.toLocaleString() }} -
            </span>
          </div>
        </div>

        <!-- 見積明細テーブル -->
        <table class="estimate-detail__table">
          <thead>
            <tr>
              <th class="text-center estimate-detail__header">商品名</th>
              <th class="text-center estimate-detail__header">数量</th>
              <th class="text-center estimate-detail__header">単価</th>
              <th class="text-center estimate-detail__header">金額</th>
            </tr>
          </thead>
          <tbody class="estimate-detail__body">
            <tr v-for="(item, index) in items" :key="index">
              <td class="estimate-detail__cell">{{ item.name }}</td>
              <td class="pl-2 text-right estimate-detail__cell">{{ item.quantity.toLocaleString() }}</td>
              <td class="pl-2 text-right estimate-detail__cell">{{ item.price.toLocaleString() }}</td>
              <td class="pl-2 text-right estimate-detail__cell">{{ (item.quantity * item.price).toLocaleString() }}</td>
            </tr>
          </tbody>
        </table>

        <!-- 合計テーブル -->
        <div class="d-flex justify-end">
          <table class="estimate-total__table">
            <tr class="estimate-total__row">
              <th class="text-center estimate-total__header">小計</th>
              <td class="pl-2 text-right estimate-total__cell">￥{{ subtotal.toLocaleString() }}</td>
            </tr>
            <tr class="estimate-total__row">
              <th class="text-center estimate-total__header">消費税（10%）</th>
              <td class="pl-2 text-right estimate-total__cell">￥{{ tax.toLocaleString() }}</td>
            </tr>
            <tr class="estimate-total__row">
              <th class="text-center estimate-total__header">合計</th>
              <td class="pl-2 text-right estimate-total__cell">￥{{ totalAmount.toLocaleString() }}</td>
            </tr>
          </table>
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
        { name: '株式会社テスト株式会社テスト株式会社テスト株式会社テスト株式会社株式会社テスト株式会社テスト株式５０', quantity: 9999, price: 1000 },
        { name: '株式会社テスト株式会社テスト株', quantity: 10, price: 5000 },
        { name: '株式会社テスト株式会社テスト株社テスト株式会社テスト株式５０', quantity: 9, price: 1000 },
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
// 見積もり依頼先
.estimate {
  &__client {
    border-bottom: 1px solid black;
    font-size: 16px;
    font-weight: bold;
    text-align: left;
    width: 420px;
  }
}
// 見積日付と見積番号 / 自社情報
.estimate-header {
  &__info {
    display: grid;
    font-size: 12px;
  }
}
// 見積金額合計
.estimate-total {
  &__box {
    border: 2px solid #000;
    display: inline-block;
  }
}

// 見積もり明細
.estimate-detail {
  &__table {
    border: 1px solid rgba(0, 0, 0, 0.12);
    border-collapse: collapse;
    font-size: 12px;
    width: 100%;

    th:nth-child(1), td:nth-child(1) { width: 65%; } // 商品
    th:nth-child(2), td:nth-child(2) { width: 5%; } // 数量
    th:nth-child(3), td:nth-child(3) { width: 15%; } // 単価
    th:nth-child(4), td:nth-child(4) { width: 15%; } // 金額

    th, td {
      border: 1px solid rgba(0, 0, 0, 0.12);
    }
  }
  &__cell {
    padding: 0 2px;
  }
}

// 合計テーブル
.estimate-total {
  &__table {
    border-collapse: collapse;
    font-size: 12px;
    margin-bottom: 24px;
    width: 284px;

    th, td {
      border: 1px solid rgba(0, 0, 0, 0.12); /* 全てのセルに適用 */
    }

    tr:first-child th,
    tr:first-child td {
      border-top: none; /* 上線を消す */
    }
  }

  &__header {
    width: 50%;
  }

  &__cell {
    width: 50%;
  }
}

.remarks {
  border: 1px solid #ccc;
  padding: 16px;
}


// 印刷用表示
@media print {
  // 見積もり依頼先
  .estimate {
    &__client {
      border-bottom: 1px solid black;
      font-size: 16px;
      font-weight: bold;
      text-align: left;
      width: 420px;
    }
  }
  // 見積日付と見積番号 / 自社情報
  .estimate-header {
    &__info {
      display: grid;
      font-size: 10px;
    }
  }
  // 見積金額合計
  .estimate-total {
    &__box {
      border: 2px solid #000;
      display: inline-block;
    }
  }
  // 見積もり明細
  .estimate-detail {
    &__table {
      border: 1px solid rgba(0, 0, 0, 0.12);
      border-collapse: collapse;
      font-size: 10px;
      width: 100%;

      th:nth-child(1), td:nth-child(1) { width: 59%; } // 商品
      th:nth-child(2), td:nth-child(2) { width: 7%; }  // 数量
      th:nth-child(3), td:nth-child(3) { width: 17%; } // 単価
      th:nth-child(4), td:nth-child(4) { width: 17%; } // 金額
    }
  }

  // 合計テーブル
  .estimate-total {
  &__table {
    font-size: 10px;
    margin-bottom: 24px;
    width: 189px;
  }
}
}
</style>
