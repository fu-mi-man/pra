export default ({ app }, inject) => {
  // ポップアップ表示期間の設定
  const POPUP_PERIOD = {
    start: new Date(2024, 10, 10, 0, 0), // 2024/11/7 20:00
    end: new Date(2029, 10, 20, 0, 0), // 2024/11/9 00:00
  }

  // 期間設定を取得するための関数を注入
  inject('getPopupPeriod', () => POPUP_PERIOD)

  inject('shouldShowPopup', () => {
    // 日本時間で取得
    const japanTime = new Date(
      new Date().toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' })
    )

    // 「今後表示しない」にチェックしたことがあるか確認する
    const popupSettings = localStorage.getItem('popupSettings')
    if (popupSettings) {
      const settings = JSON.parse(popupSettings)

      // ポップアップ期間外の場合，ローカルストレージをクリアする
      if (new Date(settings.expireTime) < japanTime) {
        localStorage.removeItem('popupSettings')
      }
      return false
    }

    // チェックしたことがない場合
    return POPUP_PERIOD.start <= japanTime && japanTime < POPUP_PERIOD.end
  })
}
