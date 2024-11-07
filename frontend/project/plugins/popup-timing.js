export default ({ app }, inject) => {
  // ポップアップ表示期間の設定
  const POPUP_PERIOD = {
    start: new Date(2024, 10, 7, 20, 0), // 2024/11/6 21:15
    end: new Date(2024, 10, 8, 0, 0), // 2024/11/7 00:00
  }

  // 期間設定を取得するための関数を注入
  inject('getPopupPeriod', () => POPUP_PERIOD)

  inject('shouldShowPopup', () => {
    // 日本時間で取得
    const japanTime = new Date(
      new Date().toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' })
    )

    const popupSettings = localStorage.getItem('popupSettings')
    if (!popupSettings) {
      return POPUP_PERIOD.start <= japanTime && japanTime < POPUP_PERIOD.end
    }

    // 「今後表示しない」にチェックしている場合
    const settings = JSON.parse(popupSettings)

    // 期間外ならローカルストレージをクリアする
    if (new Date(settings.expireTime) < japanTime) {
      localStorage.removeItem('popupSettings')
    }

    return false
  })
}
