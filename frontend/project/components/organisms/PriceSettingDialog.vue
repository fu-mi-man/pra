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
                <div class="mb-4">
                  <label class="text-subtitle-2 mb-2 d-block">適用税率</label>
                  <tax-rate-select
                    v-model="taxRate"
                    @input="handleTaxRateChange"
                  />
                </div>
                <v-card-subtitle class="px-0 text-subtitle-1">
                  基本価格
                </v-card-subtitle>

                <v-card outlined class="pa-3 mb-4">
                  <v-card-text class="">
                    <span class="pa-3 rounded grey lighten-4 font-weight-bold">
                      通常価格
                    </span>
                  </v-card-text>
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
      taxRate: 10  // 追加
    }
  },

  methods: {
    handleTaxRateChange(newRate) {
      // 税率変更時の処理
      console.log('Tax rate changed:', newRate)
    },
    handleSave() {
      // 保存処理を実装予定
      this.dialog = false
    }
  }
}
</script>
<style scoped>
</style>
