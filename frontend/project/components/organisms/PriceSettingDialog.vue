<template>
  <div>
    <!-- ダイアログを開くボタン -->
    <v-btn
      color="primary"
      @click="dialog = true"
    >
      価格設定
    </v-btn>

    <!-- 価格設定ダイアログ -->
    <v-dialog
      v-model="dialog"
      max-width="1000px"
      :fullscreen="$vuetify.breakpoint.mobile"
    >
      <v-card>
        <v-card-title>
          <span class="text-h5">価格設定</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-card-subtitle class="px-0 text-subtitle-1">
                  適用税率
                </v-card-subtitle>
                <div class="mb-4">
                  <!-- <label class="text-subtitle-2 mb-2 d-block">適用税率</label> -->
                  <tax-rate-select
                    v-model="taxRate"
                  />
                </div>
                <v-card-subtitle class="px-0 text-subtitle-1">
                  基本価格
                </v-card-subtitle>

                <v-card outlined class="pa-10 mb-4">
                  <span class="pa-3 rounded grey lighten-4 font-weight-bold">
                    通常価格
                  </span>
                  <!-- 価格を表示 / 価格を非表示 -->
                  <v-radio-group
                    v-model="priceVisibility"
                    row
                    class="mt-4"
                    hide-details
                  >
                    <v-radio
                      label="価格を表示"
                      value="visible"
                    ></v-radio>
                    <v-radio
                      label="価格を非表示"
                      value="hidden"
                      class="ml-4"
                    ></v-radio>
                  </v-radio-group>

                  <!-- 税別価格/税込価格の選択（価格表示時のみ） -->
                  <v-radio-group
                    v-if="priceVisibility === 'visible'"
                    v-model="priceType"
                    row
                    class="mt-4"
                    hide-details
                  >
                    <v-radio
                      label="税別価格で入力"
                      value="excludingTax"
                    ></v-radio>
                    <v-radio
                      label="税込価格で入力"
                      value="includingTax"
                      class="ml-4"
                    ></v-radio>
                  </v-radio-group>

                  <!-- 価格入力部 -->
                  <div v-if="priceVisibility === 'visible'" class="mt-4">
                    <label class="mb-2 text-subtitle-2 d-block">
                      {{ priceType === 'excludingTax' ? '税別価格' : '税込価格' }}
                    </label>
                    <div class="d-flex align-center mb-5">
                      <v-text-field
                        v-model.number="inputPrice"
                        type="number"
                        dense
                        outlined
                        hide-details
                        hide-spin-buttons
                        class="max-width-200"
                        min="0"
                        suffix="円"
                        @input="handlePriceInput"
                      >
                      </v-text-field>
                    </div>

                    <!-- 算出価格 -->
                    <div class="pa-3 rounded grey lighten-4">
                      <span class="text-subtitle-2">
                        {{ priceType === 'excludingTax' ? '税込価格' : '税別価格' }}:
                      </span>
                      <span class="ml-2">
                        ¥{{ priceType === 'excludingTax' ? priceIncludingTax.toLocaleString() : priceExcludingTax.toLocaleString() }}
                      </span>
                    </div>

                    <!-- 価格備考 -->
                    <div class="mt-4">
                      <label class="mb-2 text-subtitle-2 d-block">
                        価格備考
                      </label>
                      <v-textarea
                        v-model="priceNote"
                        outlined
                        dense
                        hide-details
                        placeholder="例：期間限定価格"
                        rows="3"
                      ></v-textarea>
                    </div>
                  </div>

                  <!-- 価格を非表示の場合のみ表示 -->
                  <div v-if="priceVisibility === 'hidden'" class="mt-4">
                    <label class="mb-2 text-subtitle-2 d-block">
                      表示文言
                    </label>
                    <v-text-field
                      v-model="customText"
                      dense
                      outlined
                      hide-details
                      placeholder="例：オープン価格"
                    ></v-text-field>
                  </div>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="grey darken-1"
            text
            @click="dialog = false"
          >
            キャンセル
          </v-btn>
          <v-btn
            color="primary"
            @click="handleSave"
          >
            設定する
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import TaxRateSelect from '~/components/atoms/selects/TaxRateSelect.vue'

export default {
  components: {
    TaxRateSelect,
  },
  name: 'PriceSettingDialog',

  data() {
    return {
      dialog: false,
      taxRate: 10,                // 適用税率
      priceVisibility: 'visible', // 価格の表示/非表示
      priceType: 'excludingTax',  // 税別価格/税込価格で入力
      inputPrice: 0,              // 入力価格
      priceExcludingTax: 0,       // 税別価格
      priceIncludingTax: 0,       // 税込価格
      priceNote: '',              // 価格備考
      customText: '',             // 価格非表示時の表示文言
    }
  },
  watch: {
    /**
     * 適用税率の変更を監視し，価格を再計算
     * @param {number} newRate - 新しい税率（8% or 10%）
     */
    taxRate: {
      handler(newRate) {
        this.calculatePrice();
      }
    },
    /**
     * 税別/税込の入力方式の変更を監視し，価格を再計算
     * @param {string} newType - 新しい価格タイプ（'excludingTax' or 'includingTax'）
     */
    priceType: {
      handler(newType) {
        this.calculatePrice()
      },
      immediate: true
    }
  },
  methods: {
    /**
     * 税込価格と税別価格を計算
     * priceTypeに応じて入力価格から価格を算出
     */
    calculatePrice() {
      if (!this.inputPrice) return;

      if (this.priceType === 'excludingTax') {
        this.priceExcludingTax = this.inputPrice;
        this.priceIncludingTax = Math.round(this.inputPrice * (1 + this.taxRate / 100));
      } else {
        this.priceIncludingTax = this.inputPrice;
        this.priceExcludingTax = Math.round(this.inputPrice / (1 + this.taxRate / 100));
      }
    },
    /**
     * 価格入力時のハンドラー
     * 入力された価格を数値に変換し、税込/税別価格を計算
     * @param {string|number} value - 入力された価格
     */
    handlePriceInput(value) {
      this.inputPrice = Number(value)
      this.calculatePrice();
    },
    handleSave() {
      // 保存処理を実装予定
      this.dialog = false
    }
  }
}
</script>
<style scoped>
.max-width-200 {
  max-width: 200px;
}

</style>
