## ローカル環境構築

1. `docker compose up -d --build` でコンテナを作成する
2. `make shapp` で app コンテナ（nuxt のコンテナ）に接続する
3. `yarn dev` でビルドする
4. `http://localhost:3000/`　に接続する
5. `http://localhost:3000/list`　に接続する

コンテナの初回起動時のみ yarn install が必要 2 回目以降は `yarn dev` だけで十分

<br>

## Vuetify テキストサイズクラス一覧

## 基本サイズ

| クラス名        | サイズ(rem) | サイズ(px) |
| --------------- | ----------- | ---------- |
| text-h1         | 6rem        | 96px       |
| text-h2         | 3.75rem     | 60px       |
| text-h3         | 3rem        | 48px       |
| text-h4         | 2.125rem    | 34px       |
| text-h5         | 1.5rem      | 24px       |
| text-h6         | 1.25rem     | 20px       |
| text-subtitle-1 | 1rem        | 16px       |
| text-subtitle-2 | 0.875rem    | 14px       |
| text-body-1     | 1rem        | 16px       |
| text-body-2     | 0.875rem    | 14px       |
| text-button     | 0.875rem    | 14px       |
| text-caption    | 0.75rem     | 12px       |
| text-overline   | 0.75rem     | 12px       |

## ブレークポイント（レスポンシブ）プレフィックス

| プレフィックス | 画面幅      | デバイス       |
| -------------- | ----------- | -------------- |
| xs             | 600px 未満  | スマートフォン |
| sm             | 600px 以上  | タブレット     |
| md             | 960px 以上  | PC             |
| lg             | 1264px 以上 | ワイド PC      |
| xl             | 1904px 以上 | 4K 以上        |

※xs を明示する書き方はレガシーの書き方

## 使用例

```vue
<p
  class="
  text-caption      <!-- スマホ: 12px -->
  text-sm-body-2    <!-- タブレット: 14px -->
  text-md-body-1    <!-- PC: 16px -->
">
  テキスト
</p>
```

# Vuetify 表示制御クラス一覧

## Hidden クラス

| クラス名       | 非表示になる画面サイズ             |
| -------------- | ---------------------------------- |
| hidden-xs-only | xs のみ（600px 未満）              |
| hidden-sm-only | sm のみ（600px 以上 960px 未満）   |
| hidden-md-only | md のみ（960px 以上 1264px 未満）  |
| hidden-lg-only | lg のみ（1264px 以上 1904px 未満） |
| hidden-xl-only | xl のみ（1904px 以上）             |

## Hidden and Down（指定サイズ以下で非表示）

| クラス名           | 非表示になる画面サイズ |
| ------------------ | ---------------------- |
| hidden-sm-and-down | sm 以下（960px 未満）  |
| hidden-md-and-down | md 以下（1264px 未満） |
| hidden-lg-and-down | lg 以下（1904px 未満） |

## Hidden and Up（指定サイズ以上で非表示）

| クラス名         | 非表示になる画面サイズ |
| ---------------- | ---------------------- |
| hidden-sm-and-up | sm 以上（600px 以上）  |
| hidden-md-and-up | md 以上（960px 以上）  |
| hidden-lg-and-up | lg 以上（1264px 以上） |

## 使用例

```vue
<!-- PCサイズ以上で非表示 -->
<div class="hidden-md-and-up">
  スマホ・タブレットでのみ表示
</div>

<!-- スマホサイズ以下で非表示 -->
<div class="hidden-sm-and-down">
  PCでのみ表示
</div>

<!-- タブレットサイズのみ表示 -->
<div class="hidden-xs-only hidden-md-and-up">
  タブレットでのみ表示
</div>
```

## ブレークポイント参考

| サイズ | 画面幅      | デバイス       |
| ------ | ----------- | -------------- |
| xs     | 600px 未満  | スマートフォン |
| sm     | 600px 以上  | タブレット     |
| md     | 960px 以上  | PC             |
| lg     | 1264px 以上 | ワイド PC      |
| xl     | 1904px 以上 | 4K 以上        |

`display-*` クラスと組み合わせることで、より細かい表示制御も可能です。

<br>

# Vuetify 印刷用表示制御クラス

印刷時のレイアウトを制御するためのユーティリティクラスです。画面サイズによるレスポンシブ対応とは別に、印刷時の表示/非表示を指定できます。

## Display Print クラス一覧

| クラス名             | 説明                                       |
| -------------------- | ------------------------------------------ |
| d-print-none         | 印刷時に要素を非表示にします               |
| d-print-block        | 印刷時に block 要素として表示します        |
| d-print-inline       | 印刷時に inline 要素として表示します       |
| d-print-inline-block | 印刷時に inline-block 要素として表示します |
| d-print-flex         | 印刷時に flex 要素として表示します         |
| d-print-inline-flex  | 印刷時に inline-flex 要素として表示します  |

## 使用例

レスポンシブ対応と印刷時の表示を組み合わせる例：

```vue
<!-- PC・タブレットサイズ以上（960px以上）で表示、印刷時も表示 -->
<h2 class="hidden-sm-and-down d-print-block">
  PCレイアウト
</h2>

<!-- スマホ・タブレットサイズ（959px以下）で表示、印刷時は非表示 -->
<h2 class="hidden-md-and-up d-print-none">
  スマホレイアウト
</h2>
```

## 一般的な使用パターン

- PC 用レイアウトには `d-print-block` を追加
  - 印刷時は PC レイアウトで出力されます
- スマホ用レイアウトには `d-print-none` を追加
  - 印刷時にスマホ用レイアウトは非表示になります

これにより、画面サイズに応じた表示制御と、印刷時の表示制御を別々に管理できます。印刷時は通常 PC レイアウトが適していることが多いため、このパターンが推奨されます。

## 注意点

- 印刷用クラスは画面表示には影響しません
- レスポンシブクラス（hidden-\*）と組み合わせて使用できます
- 印刷プレビューで表示を確認することをお勧めします
