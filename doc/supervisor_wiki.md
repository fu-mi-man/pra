# Supervisor 
## 🚡 Supervisor（スーパーバイザー）とは
- Linux サーバー上でプログラムやスクリプトを常に動かし続けるための監視・管理ツール
- 非同期処理（ジョブ）を実行する際に使用されることが多い

## 📦 インストール方法

### パッケージマネージャー（推奨）
#### CentOS 8以降 / Amazon Linux 2023
```bash
sudo dnf install supervisor
```

#### CentOS 7 / Amazon Linux 2
```bash
sudo yum install supervisor
```

### pip インストール（代替手段）
パッケージが利用できない場合のみ使用
```bash
# Python3とpipのインストール
sudo yum install python3-pip

# Supervisor インストール
sudo pip3 install supervisor
```

## ✅ インストール確認

```bash
# バージョン確認
supervisord --version
# 4.2.5

# コマンド場所確認
which supervisord
# /usr/local/bin/supervisord（pipでインストールした場合）
# /usr/bin/supervisord（パッケージマネージャー（dnf/yum）の場合）

which supervisorctl
# /usr/local/bin/supervisorctl
```

## systemd サービス設定
**※サーバー再起動時に自動起動させたい場合，systemdサービス化が必要**  
⇒**設定する必要がある**

自動起動が有効かどうか確認
```bash
sudo systemctl is-enabled supervisord
# Failed to get unit file state for supervisord.service: No such file or directory（未設定時のエラー）
```

システム起動時にsupervisordを自動起動する設定
```bash
sudo systemctl enable supervisord
# Failed to enable unit: Unit file supervisord.service does not exist.（未設定時のエラー）
```

## systemdサービスファイルの作成
### pipでのインストールの場合の設定

サービスファイルを作成
```bash
sudo vi /etc/systemd/system/supervisord.service
```

サービスファイルの内容
```ini
[Unit]
Description=Supervisor daemon
After=network.target

[Service]
Type=forking
ExecStart=/usr/local/bin/supervisord -c /etc/supervisor/supervisord.conf
ExecReload=/bin/kill -HUP $MAINPID
KillMode=process
Restart=on-failure
RestartSec=42s

[Install]
WantedBy=multi-user.target
```

systemdに認識させる
```bash
sudo systemctl daemon-reload
sudo systemctl enable supervisord
```

supervisordサービスを開始
```bash
sudo systemctl start supervisord
```

## Supervisor 設定ファイル

### メイン設定ファイル
**ファイル:** `/etc/supervisor/supervisord.conf`
```ini
; supervisor config file

[unix_http_server]
file=/var/run/supervisor.sock   ; (the path to the socket file)
chmod=0700                       ; sockef file mode (default 0700)

[supervisord]
logfile=/var/log/supervisor/supervisord.log ; (main log file;default $CWD/supervisord.log)
pidfile=/var/run/supervisord.pid ; (supervisord pidfile;default supervisord.pid)
childlogdir=/var/log/supervisor            ; ('AUTO' child log dir, default $TEMP)

; the below section must remain in the config file for RPC
; (supervisorctl/web interface) to work, additional interfaces may be
; added by defining them in separate rpcinterface: sections
[rpcinterface:supervisor]
supervisor.rpcinterface_factory = supervisor.rpcinterface:make_main_rpcinterface

[supervisorctl]
serverurl=unix:///var/run/supervisor.sock ; use a unix:// URL  for a unix socket

; The [include] section can just contain the "files" setting.  This
; setting can list multiple files (separated by whitespace or
; newlines).  It can also contain wildcards.  The filenames are
; interpreted as relative to this file.  Included files *cannot*
; include files themselves.

[include]
files = /etc/supervisor/conf.d/*.conf
```

### Laravel Scheduler 設定
**ファイル:** `/etc/supervisor/conf.d/laravel-scheduler.conf`
```ini
[program:laravel-scheduler]
process_name=%(program_name)s_%(process_num)02d
command=php /mnt/data/jb-002/catalog/php/app/artisan schedule:work
directory=/mnt/data/jb-002/catalog/php/app
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=ec2-user
numprocs=1
redirect_stderr=true
stdout_logfile=/mnt/data/jb-002/catalog/php/app/storage/logs/laravel-schedule-worker.log
stopwaitsecs=3600
```

### Laravel Worker 設定
**ファイル:** `/etc/supervisor/conf.d/laravel-worker.conf`
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /mnt/data/jb-002/catalog/php/app/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=ec2-user
numprocs=4
redirect_stderr=true
stdout_logfile=/mnt/data/jb-002/catalog/php/app/storage/logs/laravel-queue-worker.log
stopwaitsecs=3600
```

## Supervisor 基本コマンド

### Supervisor本体の制御
#### 起動
```bash
# Supervisor本体を起動
sudo supervisord -c /etc/supervisor/supervisord.conf
# 成功時: 何も表示されない
# エラー時: Error: Another program is already listening...（既に起動済み）
```

#### 停止
```bash
# Supervisor本体を停止（全プロセスも停止）
sudo supervisorctl shutdown
# Shut down
```

#### 状態確認
```bash
# Supervisor本体のプロセス確認
ps aux | grep supervisord
```

### 管理プロセスの状態確認
#### 全プロセスの状態確認
```bash
sudo supervisorctl status
# laravel-scheduler:laravel-scheduler_00   RUNNING   pid 312595, uptime 2 days, 0:38:22
# laravel-worker:laravel-worker_00         RUNNING   pid 504881, uptime 0:27:06
# laravel-worker:laravel-worker_01         RUNNING   pid 504892, uptime 0:27:00
# laravel-worker:laravel-worker_02         RUNNING   pid 504897, uptime 0:26:57
# laravel-worker:laravel-worker_03         RUNNING   pid 504887, uptime 0:27:02
```

#### 特定のプロセスの状態確認
```bash
sudo supervisorctl status laravel-worker:*
# laravel-worker:laravel-worker_00   RUNNING   pid 504881, uptime 0:27:38
# laravel-worker:laravel-worker_01   RUNNING   pid 504892, uptime 0:27:32
# laravel-worker:laravel-worker_02   RUNNING   pid 504897, uptime 0:27:29
# laravel-worker:laravel-worker_03   RUNNING   pid 504887, uptime 0:27:34

sudo supervisorctl status laravel-scheduler:*
# laravel-scheduler:laravel-scheduler_00   RUNNING   pid 312595, uptime 2 days, 0:39:44
```

### 管理プロセスの制御コマンド
#### 開始
```bash
# 特定のプロセスを開始
sudo supervisorctl start laravel-worker:laravel-worker_00

# 全プロセスを開始
sudo supervisorctl start all
```

#### 停止
```bash
# 特定のプロセスを停止
sudo supervisorctl stop laravel-worker:laravel-worker_00

# 全プロセスを停止
sudo supervisorctl stop all
```

#### 再起動
```bash
# 特定のプロセスを再起動
sudo supervisorctl restart laravel-worker:laravel-worker_00

# 全プロセスを再起動
sudo supervisorctl restart all
```

### 設定変更時のコマンド
```bash
# 設定ファイルの再読み込み
sudo supervisorctl reread
# No config updates to processes（変更なしのとき）
# laravel-scheduler: changed（変更があったとき）

# 設定の適用
sudo supervisorctl update
# 変更なしのときは，何も表示されない
# laravel-scheduler: stopped
# laravel-scheduler: updated process group（変更があったとき）
```
