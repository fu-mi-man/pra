// オブジェクトインポート/エクスポート
const POPUP_PERIOD = {
  start: new Date(2024, 10, 10, 0, 0),
  end: new Date(2024, 10, 20, 0, 0),
}

// 各関数をオブジェクトにまとめる
function getPopupPeriod() {
  return POPUP_PERIOD
}

function checkPopupDisplayStatus() {
  const japanTime = new Date(
    new Date().toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' })
  )

  const popupSettings = localStorage.getItem('popupSettings')
  if (popupSettings) {
    const settings = JSON.parse(popupSettings)

    if (new Date(settings.expireTime) < japanTime) {
      localStorage.removeItem('popupSettings')
    }
    return false
  }

  return POPUP_PERIOD.start <= japanTime && japanTime < POPUP_PERIOD.end
}

// オブジェクトとしてエクスポート
export default {
  getPopupPeriod,
  checkPopupDisplayStatus,
}

// nuxt.config.jsにインジェクション
// export default ({ app }, inject) => {
//   // ポップアップ表示期間の設定
//   const POPUP_PERIOD = {
//     start: new Date(2024, 10, 10, 0, 0), // 2024/11/7 20:00
//     end: new Date(2024, 10, 20, 0, 0), // 2024/11/9 00:00
//   }
//
//   // 期間設定を取得するための関数を注入
//   inject('getPopupPeriod', () => POPUP_PERIOD)
//
//   inject('checkPopupDisplayStatus', () => {
//     // 日本時間で取得
//     const japanTime = new Date(
//       new Date().toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' })
//     )
//
//     // 「今後表示しない」にチェックしたことがあるか確認する
//     const popupSettings = localStorage.getItem('popupSettings')
//     if (popupSettings) {
//       const settings = JSON.parse(popupSettings)
//
//       // ポップアップ期間外の場合，ローカルストレージをクリアする
//       if (new Date(settings.expireTime) < japanTime) {
//         localStorage.removeItem('popupSettings')
//       }
//       return false
//     }
//
//     // チェックしたことがない場合
//     return POPUP_PERIOD.start <= japanTime && japanTime < POPUP_PERIOD.end
//   })
// }

// 名前付きでエクスポート
// const POPUP_PERIOD = {
//   start: new Date(2024, 10, 10, 0, 0),
//   end: new Date(2024, 10, 20, 0, 0),
// }
//
// /**
//  * ポップアップ期間を取得する
//  */
// export function getPopupPeriod() {
//   return POPUP_PERIOD
// }
//
// /**
//  * ポップアップを表示するかチェックする
//  */
// export function checkPopupDisplayStatus() {
//   const japanTime = new Date(
//     new Date().toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' })
//   )
//
//   // 「今後表示しない」にチェックしたことがあるか確認する
//   const popupSettings = localStorage.getItem('popupSettings')
//   if (popupSettings) {
//     const settings = JSON.parse(popupSettings)
//
//     // ポップアップ期間外の場合，ローカルストレージをクリアする
//     if (new Date(settings.expireTime) < japanTime) {
//       localStorage.removeItem('popupSettings')
//     }
//     return false
//   }
//
//   // チェックしたことがない場合
//   return POPUP_PERIOD.start <= japanTime && japanTime < POPUP_PERIOD.end
// }
