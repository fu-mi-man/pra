## ローカル環境構築
1. `docker compose up -d --build` でコンテナを作成する
2. `make shapp` で app コンテナ（nuxt のコンテナ）に接続する
3. `yarn dev` でビルドする
4. `http://localhost:3000/`　に接続する

コンテナの初回起動時のみ yarn install が必要  
2回目以降は `yarn dev` だけで十分
