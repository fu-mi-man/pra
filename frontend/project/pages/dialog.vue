<template>
  <div>
    <v-btn color="primary" @click="dialog = true"> ダイアログを開く </v-btn>

    <v-dialog v-model="dialog" width="700">
      <v-card>
        <v-card-title class="text-body-1 text-sm-body-2 text-md-h6">
          SAFARI GAMES からのお知らせ
        </v-card-title>

        <v-card-text>
          <div class="custom-content">
            <div class="text-center header-section">
              <!-- PC・タブレットサイズ以上（960px以上）で表示 -->
              <h2
                class="hidden-sm-and-down text-md-h6 line-height-2 black--text font-weight-bold"
              >
                モストラは<br />
                SAFARI GAMES が提供するBtoBサービスです
              </h2>
              <!-- スマホ・タブレットサイズ（959px以下）で表示 -->
              <h2
                class="hidden-md-and-up text-body-1 line-height-2 black--text font-weight-bold"
              >
                モストラは<br />
                SAFARI GAMES が提供する<br />
                BtoBサービスです
              </h2>
            </div>
            <v-row
              class="content-grid"
              :class="{ 'mx-0': $vuetify.breakpoint.smAndDown }"
            >
              <v-col
                cols="6"
                :class="{ 'px-1': $vuetify.breakpoint.smAndDown }"
              >
                <div class="grid-item">
                  <img src="/api/placeholder/200/200" alt="サンプル画像" />
                  <p class="text-caption text-sm-body-2 text-md-body-1">簡単操作でカタログ、商品を公開</p>
                </div>
              </v-col>

              <v-col
                cols="6"
                :class="{ 'px-1': $vuetify.breakpoint.smAndDown }"
              >
                <div class="grid-item">
                  <img src="/api/placeholder/200/200" alt="サンプル画像" />
                  <p class="text-caption text-sm-body-2 text-md-body-1">商品の見積依頼、受発注が可能</p>
                </div>
              </v-col>
            </v-row>
            <div class="text-center">
              <p
                class="text-body-1 text-sm-body-2 text-md-h6 black--text font-weight-bold"
              >
                初期費用 ¥0<br />
                31日間無料でお試しできます
              </p>
              <div class="my-2">
                <v-btn
                  class="mb-4"
                  :block="$vuetify.breakpoint.smAndDown"
                  large
                  color="primary"
                  dark
                >
                  詳しくはこちら
                </v-btn>
              </div>
              <p class="text-body-2 text-sm-body-1 text-md-h6">
                お気軽にお問い合わせください
              </p>
              <p class="text-caption text-sm-body-2 text-md-body-1">
                Powered by mostra
              </p>
              <p class="text-body-2 text-sm-body-1 text-md-h6">
                https://mostra-web.com
              </p>
              <div class="text-center">
                <v-checkbox
                  v-model="ex4"
                  label="今後このお知らせを表示しない"
                  color="indigo"
                  value="indigo"
                  hide-details
                  class="d-inline-flex mb-4"
                  dense
                ></v-checkbox>
              </div>
              <p class="text-body-2 text-sm-body-1 text-md-h6">
                閉じる
              </p>
            </div>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: 'DialogExample',
  data() {
    return {
      dialog: false,
    }
  },
}
</script>

<style scoped>
.custom-content {
  font-family: Arial, sans-serif;
}

.grid-item {
  background: #f5f5f5;
  padding: 15px;
  border-radius: 8px;
  text-align: center;
}

.grid-item img {
  max-width: 100%;
  height: auto;
}

.d-inline-flex ::v-deep .v-label {
  font-size: 14px !important;
}

@media (max-width: 959px) {
  .grid-item {
    padding: 8px; /* スマホではパディングを小さく */
  }

  .content-grid {
    margin: 0 -4px; /* ネガティブマージンで端までいっぱいに */
  }
}

/* PC表示時のスタイル */
@media (min-width: 960px) {
  .content-grid {
    margin: 0 auto;
    max-width: 1200px;
  }

  .grid-item {
    margin: 0 8px;
  }
}

</style>
