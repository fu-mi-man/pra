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
                <!-- 適用税率 -->
                <v-card-subtitle class="px-0 text-subtitle-1">
                  適用税率
                </v-card-subtitle>
                <div class="mb-4">
                  <consumption-tax-select
                    v-model="taxRate"
                  />
                </div>
                <!-- 基本価格 -->
                <v-card-subtitle class="px-0 text-subtitle-1">
                  基本価格
                </v-card-subtitle>
                <v-card outlined class="pa-6 mb-6">
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
                        v-model="inputPrice"
                        type="number"
                        dense
                        outlined
                        hide-details
                        hide-spin-buttons
                        class="max-width-200"
                        min=""
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
                </v-card>


                <v-card outlined class="pa-6 mb-6">
                  <span class="pa-3 rounded grey lighten-4 font-weight-bold">
                    全顧客価格
                  </span>
                  <!-- 価格を表示 / 価格を非表示 -->
                  <v-radio-group
                    v-model="allCustomerPriceVisibility"
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

                    <!-- 価格表示設定後のコンテンツ -->
                  <template v-if="allCustomerPriceVisibility">

                    <!-- 税別価格で入力 / 税込価格で入力 -->
                    <v-radio-group
                      v-if="allCustomerPriceVisibility === 'visible'"
                      v-model="allCustomerPriceType"
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
                    <div v-if="allCustomerPriceVisibility === 'visible'" class="mt-4">
                      <label class="mb-2 text-subtitle-2 d-block">
                        {{ allCustomerPriceType === 'excludingTax' ? '税別価格' : '税込価格' }}
                      </label>
                      <div class="d-flex align-center mb-5">
                        <v-text-field
                          v-model="allCustomerInputPrice"
                          type="number"
                          dense
                          outlined
                          hide-details
                          hide-spin-buttons
                          class="max-width-200"
                          min="0"
                          suffix="円"
                          @input="handleAllCustomerPriceInput"
                        >
                        </v-text-field>
                      </div>

                      <!-- 算出価格 -->
                      <div class="pa-3 rounded grey lighten-4">
                        <span class="text-subtitle-2">
                          {{ allCustomerPriceType === 'excludingTax' ? '税込価格' : '税別価格' }}:
                        </span>
                        <span class="ml-2">
                          ¥{{ allCustomerPriceType === 'excludingTax' ? allCustomerPriceIncludingTax.toLocaleString() : allCustomerPriceExcludingTax.toLocaleString() }}
                        </span>
                      </div>
                    </div>

                    <!-- 価格を非表示の場合のみ表示 -->
                    <div v-if="allCustomerPriceVisibility === 'hidden'" class="mt-4">
                      <label class="mb-2 text-subtitle-2 d-block">
                        表示文言
                      </label>
                      <v-text-field
                        v-model="allCustomerCustomText"
                        dense
                        outlined
                        hide-details
                        placeholder="例：オープン価格"
                      ></v-text-field>
                    </div>

                    <!-- 価格備考 -->
                    <div class="mt-4">
                      <label class="mb-2 text-subtitle-2 d-block">
                        価格備考
                      </label>
                      <v-textarea
                        v-model="allCustomerPriceNote"
                        outlined
                        dense
                        hide-details
                        placeholder="例：期間限定価格"
                        rows="3"
                      ></v-textarea>
                    </div>
                  </template>
                </v-card>


                <!-- 追加グループ価格 -->
                <v-card-subtitle class="px-0 text-subtitle-1 d-flex justify-space-between align-center">
                  <span>グループ価格</span>
                  <v-btn
                    v-if="additionalGroups.length < 5"
                    color="primary"
                    text
                    @click="addPriceGroup"
                  >
                    <v-icon left>mdi-plus-circle</v-icon>
                    グループを追加
                  </v-btn>
                </v-card-subtitle>

                <!-- 追加グループのカード -->
                <v-card
                  v-for="(group) in additionalGroups"
                  :key="group.id"
                  outlined
                  class="pa-6 mb-6"
                >
                  <div class="d-flex justify-space-between align-center mb-4">
                    <v-text-field
                      v-model="group.name"
                      dense
                      outlined
                      hide-details
                      class="max-width-200"
                    ></v-text-field>
                    <v-btn
                      icon
                      color="red"
                      @click="removePriceGroup(group.id)"
                    >
                      <v-icon>mdi-minus-circle</v-icon>
                    </v-btn>
                  </div>

                  <!-- 価格を表示 / 価格を非表示 -->
                  <v-radio-group
                    v-model="group.isVisible"
                    row
                    class="mt-4"
                    hide-details
                  >
                    <v-radio
                      label="価格を表示"
                      :value="true"
                    ></v-radio>
                    <v-radio
                      label="価格を非表示"
                      :value="false"
                      class="ml-4"
                    ></v-radio>
                  </v-radio-group>

                  <!-- 税別価格/税込価格の選択（価格表示時のみ） -->
                  <v-radio-group
                    v-if="group.isVisible"
                    v-model="group.priceType"
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
                  <div v-if="group.isVisible" class="mt-4">
                    <label class="mb-2 text-subtitle-2 d-block">
                      {{ group.priceType === 'excludingTax' ? '税別価格' : '税込価格' }}
                    </label>
                    <div class="d-flex align-center mb-5">
                      <v-text-field
                        v-model="group.inputPrice"
                        type="number"
                        dense
                        outlined
                        hide-details
                        hide-spin-buttons
                        class="max-width-200"
                        min="0"
                        suffix="円"
                        @input="(value) => handleGroupPriceInput(group.id, value)"
                      >
                      </v-text-field>
                    </div>

                    <!-- 算出価格 -->
                    <div class="pa-3 rounded grey lighten-4">
                      <span class="text-subtitle-2">
                        {{ group.priceType === 'excludingTax' ? '税込価格' : '税別価格' }}:
                      </span>
                      <span class="ml-2">
                        ¥{{ group.priceType === 'excludingTax' ? group.priceIncludingTax.toLocaleString() : group.priceExcludingTax.toLocaleString() }}
                      </span>
                    </div>
                  </div>

                  <!-- 価格を非表示の場合のみ表示 -->
                  <div v-if="!group.isVisible" class="mt-4">
                    <label class="mb-2 text-subtitle-2 d-block">
                      表示文言
                    </label>
                    <v-text-field
                      v-model="group.customText"
                      dense
                      outlined
                      hide-details
                      placeholder="例：オープン価格"
                    ></v-text-field>
                  </div>

                  <!-- 価格備考 -->
                  <div class="mt-4">
                    <label class="mb-2 text-subtitle-2 d-block">
                      価格備考
                    </label>
                    <v-textarea
                      v-model="group.priceNote"
                      outlined
                      dense
                      hide-details
                      placeholder="例：期間限定価格"
                      rows="3"
                    ></v-textarea>
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
import ConsumptionTaxSelect from '~/components/atoms/selects/ConsumptionTaxSelect.vue'

export default {
  name: 'PriceSettingDialog',
  components: {
    ConsumptionTaxSelect,
  },

  data() {
    return {
      dialog: false,
      taxRate: 10,                // 適用税率
      priceVisibility: 'visible', // 価格の表示/非表示
      priceType: 'excludingTax',  // 税別価格/税込価格で入力
      inputPrice: '',             // 入力価格
      priceExcludingTax: 0,       // 税別価格
      priceIncludingTax: 0,       // 税込価格
      priceNote: '',              // 価格備考
      customText: '',             // 表示文言（価格非表示時）


      allCustomerPriceVisibility: '',
      allCustomerPriceType: '',
      allCustomerInputPrice: '',
      allCustomerPriceExcludingTax: 0,
      allCustomerPriceIncludingTax: 0,
      allCustomerPriceNote: '',
      allCustomerCustomText: '',

      // 追加グループ価格の状態
      additionalGroups: []
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
        this.calculateAllCustomerPrice();
        this.recalculateGroupPrices();
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
    },

    allCustomerPriceType: {
      handler() {
        this.calculateAllCustomerPrice();
      },
      immediate: true
    },

    // グループの価格タイプが変更されたときの処理を追加
    'additionalGroups': {
      deep: true,
      handler(newGroups) {
        newGroups.forEach(group => {
          if (group.inputPrice) {
            this.calculateGroupPrice(group);
          }
        });
      }
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
    /**
     * 全顧客価格の税込/税別価格を計算
     */
    calculateAllCustomerPrice() {
      if (!this.allCustomerInputPrice) return;

      if (this.allCustomerPriceType === 'excludingTax') {
        this.allCustomerPriceExcludingTax = this.allCustomerInputPrice;
        this.allCustomerPriceIncludingTax = Math.round(this.allCustomerInputPrice * (1 + this.taxRate / 100));
      } else {
        this.allCustomerPriceIncludingTax = this.allCustomerInputPrice;
        this.allCustomerPriceExcludingTax = Math.round(this.allCustomerInputPrice / (1 + this.taxRate / 100));
      }
    },
    /**
     * 全顧客価格入力時のハンドラー
     */
    handleAllCustomerPriceInput(value) {
      this.allCustomerInputPrice = Number(value);
      this.calculateAllCustomerPrice();
    },
    addPriceGroup() {
      if (this.additionalGroups.length >= 5) return;

      const newGroup = {
        id: `group${this.additionalGroups.length + 1}`,
        name: `グループ${String.fromCharCode(65 + this.additionalGroups.length)}価格`,
        isVisible: true,
        priceType: 'excludingTax',
        inputPrice: '',
        priceExcludingTax: 0,
        priceIncludingTax: 0,
        customText: '',
        priceNote: ''
      };

      this.additionalGroups.push(newGroup);
    },
    removePriceGroup(groupId) {
      const index = this.additionalGroups.findIndex(group => group.id === groupId);
      if (index !== -1) {
        this.additionalGroups.splice(index, 1);
      }
    },
    handleGroupPriceInput(groupId, value) {
      const group = this.additionalGroups.find(g => g.id === groupId);
      if (!group) return;

      group.inputPrice = Number(value);
      this.calculateGroupPrice(group);
    },
    calculateGroupPrice(group) {
      if (!group.inputPrice) return;

      if (group.priceType === 'excludingTax') {
        group.priceExcludingTax = group.inputPrice;
        group.priceIncludingTax = Math.round(group.inputPrice * (1 + this.taxRate / 100));
      } else {
        group.priceIncludingTax = group.inputPrice;
        group.priceExcludingTax = Math.round(group.inputPrice / (1 + this.taxRate / 100));
      }
    },
    recalculateGroupPrices() {
      this.additionalGroups.forEach(group => {
        this.calculateGroupPrice(group);
      });
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
