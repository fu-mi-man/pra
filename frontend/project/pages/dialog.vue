<template>
  <div>
    <v-btn color="primary" @click="dialog = true"> ダイアログを開く </v-btn>

    <v-dialog v-model="dialog" width="700">
      <v-card>
        <v-card-title class="text-body-1 text-sm-body-2 text-md-h6">
          <v-img
            :src="require('~/assets/images/news.png')"
            height="30"
            :max-width="140"
            contain
            alt="News"
          />
          <span class="ml-2">からのお知らせ</span>
        </v-card-title>

        <v-card-text>
          <div class="text-center dialog-header">
            <!-- PC・タブレットサイズ以上（960px以上）で表示 -->
            <div
              class="d-none d-md-flex align-center justify-center dialog-header__pc"
            >
              <div class="dialog-header__line">
                <img
                  :src="require('~/assets/images/mostra.png')"
                  class="dialog-header__logo"
                  contain
                  alt="news"
                />
                <span>は</span>
              </div>
              <div class="dialog-header__line">
                <img
                  :src="require('~/assets/images/mostra.png')"
                  class="pc-logo dialog-header__logo"
                  contain
                  alt="news"
                />
                <span>が提供するBtoBサービスです</span>
              </div>
            </div>
            <!-- スマホ・タブレットサイズ（959px以下）で表示 -->
            <div class="d-md-none dialog-header__sp">
              <div class="dialog-header__line">
                <img
                  src="~/assets/images/mostra.png"
                  class="dialog-header__logo"
                  alt="mostra"
                />
                <span>は</span>
              </div>
              <div class="dialog-header__line">
                <img
                  src="~/assets/images/mostra.png"
                  class="dialog-header__logo"
                  alt="mostra"
                />
                <span>が提供する</span>
              </div>
              <div class="dialog-header__line">
                <span>BtoBサービスです</span>
              </div>
            </div>
          </div>
          <v-row
            class="content-grid"
            :class="{ 'mx-0': $vuetify.breakpoint.smAndDown }"
          >
            <v-col cols="6" :class="{ 'px-1': $vuetify.breakpoint.smAndDown }">
              <div class="grid-item">
                <img src="/api/placeholder/200/200" alt="サンプル画像" />
                <p class="text-caption text-sm-body-2 text-md-body-1">
                  簡単操作でカタログ、商品を公開
                </p>
              </div>
            </v-col>

            <v-col cols="6" :class="{ 'px-1': $vuetify.breakpoint.smAndDown }">
              <div class="grid-item">
                <img src="/api/placeholder/200/200" alt="サンプル画像" />
                <p class="text-caption text-sm-body-2 text-md-body-1">
                  商品の見積依頼、受発注が可能
                </p>
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
                class="mb-4 dialog-footer__btn"
                :block="$vuetify.breakpoint.xs"
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
              https://www.yahoo.co.jp/
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
            <p class="text-body-2 text-sm-body-1 text-md-h6">閉じる</p>
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
.dialog-header {
  color: black;
  font-weight: bold;
}

.dialog-header__pc,
.dialog-header__sp {
  align-items: center;
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.dialog-header__line {
  align-items: center;
  display: flex;
  gap: 4px;
}

.dialog-header__logo {
  object-fit: contain;
  width: auto;
}

/* Mobile (default) */
.dialog-header__sp {
  font-size: 1rem;
}

.dialog-header__line {
  height: 1rem;
}

.dialog-header__logo {
  height: 1.25rem;
}

.grid-item {
  background: #f5f5f5;
  padding: 15px;
  border-radius: 4px;
  text-align: center;
}

.grid-item img {
  max-width: 100%;
  height: auto;
}

/* チェックボックスのフォントサイズ */
::v-deep .d-inline-flex .v-label {
  font-size: 14px !important;
}

/* スマホ（959px以下） */
@media (max-width: 959px) {
  .content-grid {
    margin: 0 -4px;
  }
}

/* タブレットサイズ: 600px以上 */
@media (min-width: 600px) {
  .dialog-footer__btn {
    width: 260px;
  }

  ::v-deep .d-inline-flex .v-label {
    font-size: 16px !important;
  }
}

/* PCサイズ: 960px以上 */
@media (min-width: 960px) {
  .dialog-header__pc {
    font-size: 1.5rem;
  }

  .dialog-header__line {
    height: 2rem;
  }

  .dialog-header__logo {
    height: 2.25rem;
  }

  .content-grid {
    margin: 0 auto;
    max-width: 1200px;
  }

  .dialog-footer__btn {
    width: 230px;
  }

  /* チェックボックスのフォントサイズ */
  ::v-deep .d-inline-flex .v-label {
    font-size: 16px !important;
  }
}
</style>
