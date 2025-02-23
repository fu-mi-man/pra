<?php

return [

    /*
    |--------------------------------------------------------------------------
    | バリデーション言語行
    |--------------------------------------------------------------------------
    |
    | 以下の言語行には、バリデータークラスで使用されるデフォルトのエラー
    | メッセージが含まれています。これらのルールの一部には、サイズルールなど
    | の複数のバージョンがあります。これらの各メッセージは自由に調整してください。
    |
    */

    'accepted' => ':attribute を承認してください。',
    'accepted_if' => ':other が :value の場合、:attribute を承認してください。',
    'active_url' => ':attribute は有効なURLではありません。',
    'after' => ':attribute には :date より後の日付を指定してください。',
    'after_or_equal' => ':attribute には :date 以降の日付を指定してください。',
    'alpha' => ':attribute には英字のみを指定してください。',
    'alpha_dash' => ':attribute には英数字、ハイフン、アンダースコアのみを指定してください。',
    'alpha_num' => ':attribute には英数字のみを指定してください。',
    'array' => ':attribute には配列を指定してください。',
    'ascii' => ':attribute には半角の英数字と記号のみを指定してください。',
    'before' => ':attribute には :date より前の日付を指定してください。',
    'before_or_equal' => ':attribute には :date 以前の日付を指定してください。',
    'between' => [
        'array' => ':attribute の項目数は :min 個から :max 個の間で指定してください。',
        'file' => ':attribute のファイルサイズは :min KBから :max KBの間で指定してください。',
        'numeric' => ':attribute は :min から :max の間で指定してください。',
        'string' => ':attribute は :min 文字から :max 文字の間で指定してください。',
    ],
    'boolean' => ':attribute はtrueまたはfalseを指定してください。',
    'can' => ':attribute に権限のない値が含まれています。',
    'confirmed' => ':attribute と確認用の値が一致しません。',
    'contains' => ':attribute には必要な値が含まれていません。',
    'current_password' => 'パスワードが正しくありません。',
    'date' => ':attribute は有効な日付ではありません。',
    'date_equals' => ':attribute は :date と同じ日付を指定してください。',
    'date_format' => ':attribute は :format 形式で指定してください。',
    'decimal' => ':attribute は小数点以下 :decimal 桁で指定してください。',
    'declined' => ':attribute を拒否してください。',
    'declined_if' => ':other が :value の場合、:attribute を拒否してください。',
    'different' => ':attribute と :other には異なる値を指定してください。',
    'digits' => ':attribute は :digits 桁で指定してください。',
    'digits_between' => ':attribute は :min 桁から :max 桁の間で指定してください。',
    'dimensions' => ':attribute の画像サイズが無効です。',
    'distinct' => ':attribute に重複した値が含まれています。',
    'doesnt_end_with' => ':attribute は次のいずれかで終わってはいけません: :values',
    'doesnt_start_with' => ':attribute は次のいずれかで始まってはいけません: :values',
    'email' => ':attribute には有効なメールアドレスを指定してください。',
    'ends_with' => ':attribute は次のいずれかで終わる必要があります: :values',
    'enum' => '選択された :attribute は無効です。',
    'exists' => '選択された :attribute は無効です。',
    'extensions' => ':attribute は次のいずれかの拡張子である必要があります: :values',
    'file' => ':attribute はファイルを指定してください。',
    'filled' => ':attribute には値を指定してください。',
    'gt' => [
        'array' => ':attribute には :value 個より多くの項目を指定してください。',
        'file' => ':attribute には :value KBより大きいファイルを指定してください。',
        'numeric' => ':attribute には :value より大きい値を指定してください。',
        'string' => ':attribute は :value 文字より長く指定してください。',
    ],
    'gte' => [
        'array' => ':attribute には :value 個以上の項目を指定してください。',
        'file' => ':attribute には :value KB以上のファイルを指定してください。',
        'numeric' => ':attribute には :value 以上の値を指定してください。',
        'string' => ':attribute は :value 文字以上で指定してください。',
    ],
    'hex_color' => ':attribute は有効な16進数カラーコードを指定してください。',
    'image' => ':attribute には画像ファイルを指定してください。',
    'in' => '選択された :attribute は無効です。',
    'in_array' => ':attribute が :other に存在しません。',
    'integer' => ':attribute には整数を指定してください。',
    'ip' => ':attribute には有効なIPアドレスを指定してください。',
    'ipv4' => ':attribute には有効なIPv4アドレスを指定してください。',
    'ipv6' => ':attribute には有効なIPv6アドレスを指定してください。',
    'json' => ':attribute には有効なJSON文字列を指定してください。',
    'list' => ':attribute はリスト形式で指定してください。',
    'lowercase' => ':attribute は小文字で指定してください。',
    'lt' => [
        'array' => ':attribute には :value 個より少ない項目を指定してください。',
        'file' => ':attribute には :value KBより小さいファイルを指定してください。',
        'numeric' => ':attribute には :value より小さい値を指定してください。',
        'string' => ':attribute は :value 文字より短く指定してください。',
    ],
    'lte' => [
        'array' => ':attribute には :value 個以下の項目を指定してください。',
        'file' => ':attribute には :value KB以下のファイルを指定してください。',
        'numeric' => ':attribute には :value 以下の値を指定してください。',
        'string' => ':attribute は :value 文字以下で指定してください。',
    ],
    'mac_address' => ':attribute は有効なMACアドレスを指定してください。',
    'max' => [
        'array' => ':attribute は :max 個以下の項目を指定してください。',
        'file' => ':attribute は :max KB以下のファイルを指定してください。',
        'numeric' => ':attribute は :max 以下の値を指定してください。',
        'string' => ':attribute は :max 文字以下で指定してください。',
    ],
    'max_digits' => ':attribute は :max 桁以下で指定してください。',
    'mimes' => ':attribute には以下のファイルタイプを指定してください: :values',
    'mimetypes' => ':attribute には以下のファイルタイプを指定してください: :values',
    'min' => [
        'array' => ':attribute は :min 個以上の項目を指定してください。',
        'file' => ':attribute は :min KB以上のファイルを指定してください。',
        'numeric' => ':attribute は :min 以上の値を指定してください。',
        'string' => ':attribute は :min 文字以上で指定してください。',
    ],
    'min_digits' => ':attribute は :min 桁以上で指定してください。',
    'missing' => ':attribute は存在してはいけません。',
    'missing_if' => ':other が :value の場合、:attribute は存在してはいけません。',
    'missing_unless' => ':other が :value でない限り、:attribute は存在してはいけません。',
    'missing_with' => ':values が存在する場合、:attribute は存在してはいけません。',
    'missing_with_all' => ':values が存在する場合、:attribute は存在してはいけません。',
    'multiple_of' => ':attribute は :value の倍数を指定してください。',
    'not_in' => '選択された :attribute は無効です。',
    'not_regex' => ':attribute の形式が無効です。',
    'numeric' => ':attribute には数値を指定してください。',
    'password' => [
        'letters' => ':attribute には少なくとも1つの文字を含める必要があります。',
        'mixed' => ':attribute には少なくとも大文字と小文字を1つずつ含める必要があります。',
        'numbers' => ':attribute には少なくとも1つの数字を含める必要があります。',
        'symbols' => ':attribute には少なくとも1つの記号を含める必要があります。',
        'uncompromised' => 'この :attribute は情報漏洩により公開されています。別の :attribute を選択してください。',
    ],
    'present' => ':attribute が存在している必要があります。',
    'present_if' => ':other が :value の場合、:attribute が存在している必要があります。',
    'present_unless' => ':other が :value でない限り、:attribute が存在している必要があります。',
    'present_with' => ':values が存在する場合、:attribute も存在している必要があります。',
    'present_with_all' => ':values が存在する場合、:attribute も存在している必要があります。',
    'prohibited' => ':attribute は入力禁止です。',
    'prohibited_if' => ':other が :value の場合、:attribute は入力禁止です。',
    'prohibited_unless' => ':other が :values でない限り、:attribute は入力禁止です。',
    'prohibits' => ':attribute は :other の入力を禁止しています。',
    'regex' => ':attribute の形式が無効です。',
    'required' => ':attribute は必須です。',
    'required_array_keys' => ':attribute には以下の項目を含める必要があります: :values',
    'required_if' => ':other が :value の場合、:attribute は必須です。',
    'required_if_accepted' => ':other が承認された場合、:attribute は必須です。',
    'required_if_declined' => ':other が拒否された場合、:attribute は必須です。',
    'required_unless' => ':other が :values でない限り、:attribute は必須です。',
    'required_with' => ':values が存在する場合、:attribute は必須です。',
    'required_with_all' => ':values が存在する場合、:attribute は必須です。',
    'required_without' => ':values が存在しない場合、:attribute は必須です。',
    'required_without_all' => ':values が存在しない場合、:attribute は必須です。',
    'same' => ':attribute と :other は一致する必要があります。',
    'size' => [
        'array' => ':attribute は :size 個の項目を含む必要があります。',
        'file' => ':attribute は :size KBのファイルを指定してください。',
        'numeric' => ':attribute は :size を指定してください。',
        'string' => ':attribute は :size 文字で指定してください。',
    ],
    'starts_with' => ':attribute は次のいずれかで始まる必要があります: :values',
    'string' => ':attribute には文字列を指定してください。',
    'timezone' => ':attribute には有効なタイムゾーンを指定してください。',
    'unique' => ':attribute は既に使用されています。',
    'uploaded' => ':attribute のアップロードに失敗しました。',
    'uppercase' => ':attribute は大文字で指定してください。',
    'url' => ':attribute には有効なURLを指定してください。',
    'ulid' => ':attribute は有効なULIDを指定してください。',
    'uuid' => ':attribute は有効なUUIDを指定してください。',

    /*
    |--------------------------------------------------------------------------
    | カスタムバリデーション言語行
    |--------------------------------------------------------------------------
    |
    | ここでは、「attribute.rule」という規則を使用して行に名前を付けることで、
    | 属性のカスタムバリデーションメッセージを指定できます。これにより、
    | 特定の属性ルールに特化した言語行を簡単に指定できます。
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'カスタムメッセージ',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | カスタムバリデーション属性
    |--------------------------------------------------------------------------
    |
    | 以下の言語行は、例えば"email"の代わりに「メールアドレス」のように、
    | より読みやすい名前をattributeプレースホルダーに置き換えるために指定します。
    | これはメッセージをよりきれいに表示するために役に立ちます。
    |
    */

    'attributes' => [
        'enterprise_id' => '出展者ID',
    ],

];
