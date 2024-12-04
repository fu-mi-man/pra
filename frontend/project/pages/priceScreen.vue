<template>
  <div>
    <v-container>
      <v-row>
        <v-col cols="12">
          <h1 class="text-h4 mb-6">価格設定</h1>

          <!-- 適用税率 -->
          <v-card-subtitle class="px-0 text-subtitle-1">
            適用税率
          </v-card-subtitle>
          <consumption-tax-select
            v-model="consumptionTaxRate"
            class="mb-4 consumption-tax__select"
          />

          <!-- 基本価格 -->
          <v-card-subtitle class="px-0 text-subtitle-1">
            基本価格
          </v-card-subtitle>
          <v-card outlined class="pa-4 pa-sm-6 mb-6">
            <span class="d-inline-block pa-3 rounded grey lighten-4 font-weight-bold">
              通常価格
            </span>
            <!-- 価格を表示 / 価格を非表示 -->
            <price-display-radio
              v-model="generalPriceDisplay"
              :row="$vuetify.breakpoint.smAndUp"
            />

            <!-- 税別価格/税込価格の選択（価格表示時のみ） -->
             <tax-status-radio
              v-if="generalPriceDisplay === 'visible'"
              v-model="generalTaxStatus"
              class="mt-4"
            />

            <!-- 価格入力部 -->
            <template v-if="generalPriceDisplay === 'visible'">
              <label class="d-block mt-4 mb-2 text-subtitle-2">
                {{ priceLabel }}
              </label>
              <v-text-field
                v-model="generalInputPrice"
                type="number"
                dense
                outlined
                hide-details
                hide-spin-buttons
                class="mb-5 price-field__input"
                min=""
                suffix="円"
                @input="handlePriceInput($event, 'general')"
              >
              </v-text-field>

              <!-- 税込価格/税別価格 -->
              <div class="pa-3 rounded grey lighten-4">
                <span class="text-subtitle-2">
                  {{ calculatedPriceLabel }}:
                </span>
                <span class="ml-2">
                  {{ formattedCalculatedPrice }}
                </span>
              </div>
            </template>

            <!-- 価格を非表示の場合のみ表示 -->
            <template v-if="generalPriceDisplay === 'hidden'">
              <label class="d-block mt-4 mb-2 text-subtitle-2">
                表示文言
              </label>
              <price-text-text-field
                v-model="generalCustomText"
              />
            </template>

            <!-- 価格備考 -->
            <label class="d-block mt-4 mb-2 text-subtitle-2">
              価格備考
            </label>
            <price-note-text-field
              v-model="generalPriceNote"
            />
          </v-card>

          <!-- 全顧客価格の有効/無効を切り替えるトグル -->
          <v-card-subtitle class="px-0 text-subtitle-1 d-flex align-center">
            <span>全顧客価格</span>
            <v-switch
              v-model="enableAllCustomerPrice"
              class="ml-4 mt-0"
              hide-details
              dense
              inset
            />
          </v-card-subtitle>

          <v-card v-if="enableAllCustomerPrice" outlined class="pa-6 mb-6">
            <span class="pa-3 rounded grey lighten-4 font-weight-bold">
              全顧客価格
            </span>
            <!-- 価格を表示 / 価格を非表示 -->
            <price-display-radio
              v-model="allCustomerPriceDisplay"
              class="mt-4"
            />

            <!-- 価格表示設定後のコンテンツ -->
            <template v-if="allCustomerPriceDisplay">
              <!-- 税別価格/税込価格の選択（価格表示時のみ） -->
              <tax-status-radio
                v-if="allCustomerPriceDisplay === 'visible'"
                v-model="allCustomerTaxStatus"
                class="mt-4"
              />

              <!-- 価格入力部 -->
              <template v-if="allCustomerPriceDisplay === 'visible'">
                <label class="d-block mt-4 mb-2 text-subtitle-2">
                  {{ allCustomerPriceLabel }}
                </label>
                <v-text-field
                  v-model="allCustomerInputPrice"
                  type="number"
                  dense
                  outlined
                  hide-details
                  hide-spin-buttons
                  class="mb-5 price-field__input"
                  min="0"
                  suffix="円"
                  @input="handlePriceInput($event, 'allCustomer')"
                >
                </v-text-field>

                <!-- 税込価格/税別価格 -->
                <div class="pa-3 rounded grey lighten-4">
                  <span class="text-subtitle-2">
                    {{ calculatedAllCustomerPriceLabel }}:
                  </span>
                  <span class="ml-2">
                    {{ formattedCalculatedAllCustomerPrice }}
                  </span>
                </div>
              </template>

              <!-- 価格を非表示の場合のみ表示 -->
              <template v-if="allCustomerPriceDisplay === 'hidden'">
                <label class="d-block mt-4 mb-2 text-subtitle-2">
                  表示文言
                </label>
                <v-text-field
                  v-model="allCustomerCustomText"
                  dense
                  outlined
                  hide-details
                  placeholder="例：オープン価格"
                ></v-text-field>
              </template>

              <!-- 価格備考 -->
              <label class="d-block mt-4 mb-2 text-subtitle-2">
                価格備考
              </label>
              <v-text-field
                v-model="allCustomerPriceNote"
                outlined
                dense
                hide-details
                placeholder="例：期間限定価格"
              />
            </template>
          </v-card>

          <!-- 追加グループ価格 -->
          <v-card-subtitle class="d-flex align-center px-0 text-subtitle-1">
            <span class="mr-5">グループ価格</span>
            <v-btn
              v-if="additionalGroups.length < 5"
              color="primary"
              text
              @click="showGroupSelectionDialog = true"
            >
              <v-icon>mdi-plus-circle</v-icon>
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
            <div class="d-flex mb-4">
              <div class="d-flex justify-space-between align-center flex-grow-1">
                <div class="pa-3 grey lighten-4 rounded text-body-2 group-name">
                  {{ group.name }}
                </div>
                <v-btn
                  icon
                  x-large
                  color="red"
                  @click="removePriceGroup(group.id)"
                >
                  <v-icon>mdi-minus-circle</v-icon>
                </v-btn>
              </div>
            </div>

            <!-- 価格を表示 / 価格を非表示 -->
            <price-display-radio
              v-model="group.priceDisplay"
              class="mt-4"
            />

            <!-- 税別価格/税込価格の選択（価格表示時のみ） -->
            <tax-status-radio
              v-if="group.priceDisplay === 'visible'"
              v-model="group.taxStatus"
              class="mt-4"
            />

            <!-- 価格入力部 -->
            <template v-if="group.priceDisplay === 'visible'">
              <label class="d-block mt-4 mb-2 text-subtitle-2">
                {{ getPriceLabel(group.taxStatus) }}
              </label>
              <v-text-field
                v-model="group.inputPrice"
                type="number"
                dense
                outlined
                hide-details
                hide-spin-buttons
                class="mb-5 price-field__input"
                min="0"
                suffix="円"
                @input="handlePriceInput($event, 'group', group.id)"
              />

              <!-- 税込価格/税別価格 -->
              <div class="pa-3 rounded grey lighten-4">
                <span class="text-subtitle-2">
                  {{ getCalculatedPriceLabel(group.taxStatus) }}:
                </span>
                <span class="ml-2">
                  {{ formatPrice(getDisplayPrice(group.taxStatus, group.priceIncludingTax, group.priceExcludingTax)) }}
                </span>
              </div>
            </template>

            <!-- 価格を非表示の場合のみ表示 -->
            <template v-if="group.priceDisplay === 'hidden'">
              <label class="d-block mt-4 mb-2 text-subtitle-2">
                表示文言
              </label>
              <v-text-field
                v-model="group.customText"
                dense
                outlined
                hide-details
                placeholder="例：オープン価格"
              />
            </template>

            <!-- 価格備考 -->
            <label class="d-block mt-4 mb-2 text-subtitle-2">
              価格備考
            </label>
            <v-textarea
              v-model="group.priceNote"
              outlined
              dense
              hide-details
              placeholder="例：期間限定価格"
              rows="1"
            />
          </v-card>

          <!-- 保存ボタン -->
          <div class="d-flex justify-end mt-6">
            <v-btn
              color="primary"
              :disabled="isSaveButtonDisabled"
              @click="handleSave"
            >
              保存する
            </v-btn>
          </div>
        </v-col>
      </v-row>
    </v-container>

    <group-selection-dialog
      v-model="showGroupSelectionDialog"
      :existing-group-ids="additionalGroups.map(g => g.id)"
      @group-selected="handleGroupSelected"
    />
  </div>
</template>

<script>
import PriceNoteTextField from '@/components/atoms/inputs/PriceNoteTextField.vue'
import PriceTextTextField from '@/components/atoms/inputs/PriceTextTextField.vue'
import PriceDisplayRadio from '@/components/atoms/radio/PriceDisplayTypeRadio.vue'
import TaxStatusRadio from '@/components/atoms/radio/TaxStatusRadio.vue'
import ConsumptionTaxSelect from '@/components/atoms/selects/ConsumptionTaxSelect.vue'
import GroupSelectionDialog from '@/components/organisms/GroupSelectionDialog.vue'

export default {
  name: 'PriceScreen',
  components: {
    PriceNoteTextField,
    PriceTextTextField,
    PriceDisplayRadio,
    TaxStatusRadio,
    ConsumptionTaxSelect,
    GroupSelectionDialog,
  },

  data() {
    return {
      enableAllCustomerPrice: false,  // 全顧客価格の有効/無効を制御
      consumptionTaxRate: 10,         // 適用税率
      // 通常価格
      generalPriceDisplay: 'visible', // 価格の表示/非表示
      generalTaxStatus: 'excluded',   // 税別価格/税込価格で入力
      generalInputPrice: '',          // 入力価格
      generalPriceExcludingTax: 0,    // 税別価格
      generalPriceIncludingTax: 0,    // 税込価格
      generalPriceNote: '',           // 価格備考
      generalCustomText: '',          // 表示文言（価格非表示時）
      // 全顧客価格
      allCustomerPriceDisplay: 'visible',
      allCustomerTaxStatus: 'excluded',
      allCustomerInputPrice: '',
      allCustomerPriceExcludingTax: 0,
      allCustomerPriceIncludingTax: 0,
      allCustomerPriceNote: '',
      allCustomerCustomText: '',

      // 追加グループ価格の状態
      additionalGroups: [],
      showGroupSelectionDialog: false,
      selectedGroups: [] // 選択されたグループを保持する配列
    }
  },
  computed: {
    // 入力価格のラベル（通常価格）
    priceLabel() {
      return this.getPriceLabel(this.generalTaxStatus);
    },
    // 表示価格のラベル（通常価格）
    calculatedPriceLabel() {
      return this.getCalculatedPriceLabel(this.generalTaxStatus)
    },
    formattedCalculatedPrice() {
      const price = this.getDisplayPrice(
        this.generalTaxStatus,
        this.generalPriceIncludingTax,
        this.generalPriceExcludingTax
      )
      return this.formatPrice(price)
    },
    // 入力価格のラベル（全顧客価格）
    allCustomerPriceLabel() {
      return this.getPriceLabel(this.allCustomerTaxStatus);
    },
    // 表示価格のラベル（全顧客価格）
    calculatedAllCustomerPriceLabel() {
      return this.getCalculatedPriceLabel(this.allCustomerTaxStatus)
    },
    formattedCalculatedAllCustomerPrice() {
      const price = this.getDisplayPrice(
        this.allCustomerTaxStatus,
        this.allCustomerPriceIncludingTax,
        this.allCustomerPriceExcludingTax
      )
      return this.formatPrice(price)
    },
    /**
     * 通常価格の入力が有効かどうかを判定
     */
    isGeneralPriceValid() {
      if (this.generalPriceDisplay === 'visible') {
        // 価格が0より大きいかチェック
        return Number(this.generalInputPrice) > 0;
      } else {
        // 表示文言が空白だけではないかチェック
        return this.generalCustomText.trim() !== '';
      }
    },
    // 全顧客価格の検証を追加
    isAllCustomerPriceValid() {
      // トグルがオフの場合は常にtrue
      if (!this.enableAllCustomerPrice) {
        return true;
      }

      // トグルがオンの場合は通常価格と同様の検証
      if (this.allCustomerPriceDisplay === 'visible') {
        return !!this.allCustomerInputPrice && Number(this.allCustomerInputPrice) > 0;
      } else {
        return !!this.allCustomerCustomText && this.allCustomerCustomText.trim() !== '';
      }
    },
    // 追加グループ価格の検証を追加
    areGroupPricesValid() {
      return this.additionalGroups.every(group => {
        if (group.priceDisplay === 'visible') {
          return !!group.inputPrice && Number(group.inputPrice) > 0;
        } else {
          return !!group.customText && group.customText.trim() !== '';
        }
      });
    },
    /**
     * 保存ボタンの有効/無効を制御
     */
    isSaveButtonDisabled() {
      return !this.isGeneralPriceValid ||
        !this.isAllCustomerPriceValid ||
        !this.areGroupPricesValid
    },
  },
  watch: {
    /**
     * 適用税率の変更を監視し、すべての価格を再計算
     * @param {number} newRate - 新しい税率（例: 8, 10）
     * @param {number} oldRate - 以前の税率
     */
    consumptionTaxRate: {
      handler(newRate, oldRate) {
        this.calculatePrices('general');
        this.calculatePrices('allCustomer');
        this.recalculateGroupPrices();
      }
    },
    /**
     * 全顧客価格の税別/税込入力方式の変更を監視し，価格を再計算
     * @param {'excluded'|'included'} newType - 新しい税率ステータス
     * @param {'excluded'|'included'} oldType - 以前の税率ステータス
     */
    generalTaxStatus: {
      handler(newType, oldType) {
        this.calculatePrices('general')
      },
      immediate: true
    },
    /**
     * 全顧客価格の税別/税込入力方式の変更を監視し，価格を再計算
     * @param {'excluded'|'included'} newType - 新しい税率ステータス
     * @param {'excluded'|'included'} oldType - 以前の税率ステータス
     */
    allCustomerTaxStatus: {
      handler(newType, oldType) {
        this.calculatePrices('allCustomer');
      },
      immediate: true
    },

    // グループの価格タイプが変更されたときの処理を追加
    'additionalGroups': {
      deep: true,
      handler(newGroups) {
        newGroups.forEach(group => {
          if (group.inputPrice) {
            this.calculatePrices('group', group);
          }
        });
      }
    }
  },
  methods: {
    /**
     * 入力価格のラベルを取得する
     * @param {string} taxStatus - 税率ステータス ('excluded' or 'included')
     * @returns {string} 表示用の価格ラベル ('税別価格' or '税込価格')
     */
    getPriceLabel(taxStatus) {
      return taxStatus === 'excluded' ? '税別価格' : '税込価格'
    },
    /**
     * 表示価格のラベルを取得する
     * @param {string} taxStatus - 税率ステータス ('excluded' or 'included')
     * @returns {string} 表示用の価格ラベル ('税別価格' or '税込価格')
     */
    getCalculatedPriceLabel(taxStatus) {
      return taxStatus === 'excluded' ? '税込価格' : '税別価格'
    },
    /**
     * 税込/税別に応じた価格を取得する
     * @param {string} taxStatus - 税率ステータス ('excluded' or 'included')
     * @param {number} includingTax - 税込価格
     * @param {number} excludingTax - 税別価格
     * @returns {number} 表示すべき価格
     */
    getDisplayPrice(taxStatus, includingTax, excludingTax) {
      return taxStatus === 'excluded' ? includingTax : excludingTax
    },
    /**
     * 価格を通貨フォーマットに変換する
     */
    formatPrice(price) {
      return `¥${price.toLocaleString()}`
    },
    /**
     * 価格入力時の共通ハンドラー
     * @param {string|number} value - 入力された価格
     * @param {'general'|'allCustomer'|'group'} priceType - 価格タイプ
     * @param {string} [groupId] - グループ価格の場合のグループID
     */
    handlePriceInput(value, priceType, groupId = null) {
      const numericValue = Number(value);
      let targetGroup = null; // グループ価格用の変数

      if (priceType === 'group') {
        // 指定されたgroupIdと一致するidを持つグループを検索し，条件に一致したグループオブジェクトを格納
        targetGroup = this.additionalGroups.find(g => g.id === groupId);
        if (!targetGroup) return; // 見つからない場合はundefinedを返す
      }

      switch (priceType) {
        case 'general':
          this.generalInputPrice = numericValue;
          break;
        case 'allCustomer':
          this.allCustomerInputPrice = numericValue;
          break
        case 'group': {
          targetGroup.inputPrice = numericValue;
          break;
        }
      }

      this.calculatePrices(priceType, targetGroup);
    },
    /**
     * 価格計算を実行する
     * @param {'general'|'allCustomer'|'group'} priceType - 価格タイプ
     * @param {Object} [group] - グループ価格の場合のグループオブジェクト
     */
    calculatePrices(priceType, group = null) {
      switch (priceType) {
        case 'general': {
          const { excludingTax, includingTax } = this.calculateTaxPrices(
            this.generalInputPrice,
            this.generalTaxStatus,
            this.consumptionTaxRate
          );
          this.generalPriceExcludingTax = excludingTax;
          this.generalPriceIncludingTax = includingTax;
          break;
        }
        case 'allCustomer': {
          const { excludingTax, includingTax } = this.calculateTaxPrices(
            this.allCustomerInputPrice,
            this.allCustomerTaxStatus,
            this.consumptionTaxRate
          );
          this.allCustomerPriceExcludingTax = excludingTax;
          this.allCustomerPriceIncludingTax = includingTax;
          break;
        }
        case 'group': {
          if (!group) return;
          const { excludingTax, includingTax } = this.calculateTaxPrices(
            group.inputPrice,
            group.taxStatus,
            this.consumptionTaxRate
          );
          group.priceExcludingTax = excludingTax;
          group.priceIncludingTax = includingTax;
          break;
        }
      }
    },
    /**
     * 価格を計算する共通関数
     * @param {number} inputPrice - 入力価格
     * @param {string} taxStatus - 税率ステータス ('excluded' or 'included')
     * @param {number} consumptionTaxRate - 適用税率
     * @returns {{excludingTax: number, includingTax: number}} 計算された税込・税別価格
     */
    calculateTaxPrices(inputPrice, taxStatus, consumptionTaxRate) {
      if (!inputPrice) return { excludingTax: 0, includingTax: 0 };

      const rate = 1 + consumptionTaxRate / 100;
      if (taxStatus === 'excluded') {
        return {
          excludingTax: inputPrice,
          includingTax: Math.round(inputPrice * rate)
        };
      } else {
        return {
          includingTax: inputPrice,
          excludingTax: Math.round(inputPrice / rate)
        };
      }
    },
    /**
     * すべてのグループの価格を再計算
     */
    recalculateGroupPrices() {
      this.additionalGroups.forEach(group => {
        this.calculatePrices('group', group);
      });
    },
    // グループ選択ダイアログでグループが選択された時のハンドラーを追加
    handleGroupSelected(group) {
      if (this.additionalGroups.length >= 5) return;

      const newGroup = {
        id: group.groupId,
        name: group.groupName,
        priceDisplay: 'visible',
        taxStatus: 'excluded',
        inputPrice: '',
        priceExcludingTax: 0,
        priceIncludingTax: 0,
        customText: '',
        priceNote: ''
      };

      this.additionalGroups.push(newGroup);
      this.showGroupSelectionDialog = false;
    },
    /**
     * 価格グループを削除
     * @param {string} groupId - 削除するグループのID
     */
    removePriceGroup(groupId) {
      const index = this.additionalGroups.findIndex(group => group.id === groupId);
      if (index !== -1) {
        this.additionalGroups.splice(index, 1);
      }
    },

    /**
     * 保存処理
     */
    handleSave() {
      // TODO: APIとの連携処理を実装
      // console.log('保存処理が実行されました');
    }
  }
}
</script>

<style scoped>
.price-field__input {
  max-width: 200px;
}

.group-name {
  min-width: 200px;
  word-break: break-all; /* 英数字を含む全ての文字列を、文字単位で強制的に折り返す */
}
</style>
