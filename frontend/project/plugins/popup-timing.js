export default ({ app }, inject) => {
  inject('shouldShowPopup', () => {
    const now = new Date()
    const japanTime = new Date(
      now.toLocaleString('en-US', { timeZone: 'Asia/Tokyo' })
    )
    const start = new Date('2024-10-21T21:45:00+09:00') // 日本時間 2024年10月21日 21:00:00
    const end = new Date('2024-10-21T22:00:00+09:00') // 日本時間 2024年10月21日 22:00:00
    return start <= japanTime && japanTime < end
  })
}
