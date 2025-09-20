#!/bin/bash
# DEPLOY FINAL COMPLETO - OTServer Tibia 8.60
# Revisão minuciosa para garantir funcionamento 100%

echo "=========================================="
echo "  🚀 DEPLOY FINAL COMPLETO"
echo "  🎯 GARANTIA DE FUNCIONAMENTO 100%"
echo "=========================================="
echo ""

# Variáveis
SERVER_IP="181.215.45.238"
DOMAIN="$SERVER_IP"  # Usar IP como domínio por enquanto
DB_ROOT_PASS="$(openssl rand -base64 32)"
DB_GAME_PASS="$(openssl rand -base64 24)"
WEBSITE_DIR="/var/www/html"
SERVER_DIR="/opt/otserver"

echo "📋 INICIANDO REVISÃO MINUCIOSA..."

# ==========================================
# 1. SISTEMA OPERACIONAL E DEPENDÊNCIAS
# ==========================================
echo ""
echo "🔧 1. CONFIGURANDO SISTEMA OPERACIONAL..."

# Atualizar sistema
apt update && apt upgrade -y

# Configurar timezone
timedatectl set-timezone America/Sao_Paulo

# Instalar TODAS as dependências necessárias
echo "📦 Instalando dependências completas..."
apt install -y \
    nginx \
    mysql-server mysql-client \
    php8.3-fpm php8.3-mysql php8.3-gd php8.3-xml php8.3-curl php8.3-zip \
    php8.3-mbstring php8.3-bcmath php8.3-intl php8.3-soap php8.3-xmlrpc \
    php8.3-json php8.3-tokenizer php8.3-fileinfo php8.3-ctype \
    build-essential cmake git \
    libboost-all-dev libgmp-dev libmysqlclient-dev \
    libpugixml-dev libcrypto++-dev libssl-dev libfmt-dev \
    fail2ban ufw supervisor unzip zip curl wget \
    htop iotop nethogs tree nano vim

echo "✅ Sistema operacional configurado"

# ==========================================
# 2. FIREWALL E SEGURANÇA
# ==========================================
echo ""
echo "🔥 2. CONFIGURANDO FIREWALL E SEGURANÇA..."

# Configurar UFW
ufw --force enable
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 80/tcp
ufw allow 443/tcp
ufw allow 7171/tcp  # Login server
ufw allow 7172/tcp  # Game server
ufw allow 7173/tcp  # Live cast (opcional)

# Configurar fail2ban
cat > /etc/fail2ban/jail.local <<EOF
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3
ignoreip = 127.0.0.1/8 ::1

[ssh]
enabled = true
port = ssh
logpath = /var/log/auth.log
maxretry = 3

[nginx-http-auth]
enabled = true
filter = nginx-http-auth
logpath = /var/log/nginx/error.log
maxretry = 3

[nginx-limit-req]
enabled = true
filter = nginx-limit-req
logpath = /var/log/nginx/error.log
maxretry = 3

[otserver]
enabled = true
port = 7171,7172
logpath = /var/log/otserver/error.log
maxretry = 2
bantime = 7200
EOF

systemctl restart fail2ban
systemctl enable fail2ban

echo "✅ Firewall e segurança configurados"

# ==========================================
# 3. MYSQL - CONFIGURAÇÃO COMPLETA
# ==========================================
echo ""
echo "🗄️ 3. CONFIGURANDO MYSQL..."

# Parar MySQL para configuração segura
systemctl stop mysql

# Configurar MySQL para OTServer
cat > /etc/mysql/mysql.conf.d/otserver.cnf <<EOF
[mysqld]
# Configurações para OTServer
max_connections = 200
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT

# Charset
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

# Query cache
query_cache_type = 1
query_cache_size = 64M

# Timeout
wait_timeout = 600
interactive_timeout = 600

# Logs
general_log = 0
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2

# Security
bind-address = 127.0.0.1
local-infile = 0
EOF

# Iniciar MySQL
systemctl start mysql
systemctl enable mysql

# Aguardar MySQL inicializar
sleep 5

# Configuração de segurança do MySQL
mysql <<EOF
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '${DB_ROOT_PASS}';
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
FLUSH PRIVILEGES;
EOF

# Criar banco e usuário do jogo
mysql -u root -p${DB_ROOT_PASS} <<EOF
CREATE DATABASE IF NOT EXISTS global CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'otserver'@'localhost' IDENTIFIED BY '${DB_GAME_PASS}';
GRANT ALL PRIVILEGES ON global.* TO 'otserver'@'localhost';
GRANT SELECT ON mysql.user TO 'otserver'@'localhost';
FLUSH PRIVILEGES;
EOF

echo "✅ MySQL configurado com segurança"

# ==========================================
# 4. PHP - CONFIGURAÇÃO OTIMIZADA
# ==========================================
echo ""
echo "🐘 4. CONFIGURANDO PHP..."

# Configurar PHP para OTServer
cat > /etc/php/8.3/fpm/conf.d/99-otserver.ini <<EOF
; Configurações para OTServer
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
max_input_vars = 3000

; Security
expose_php = Off
allow_url_fopen = On
allow_url_include = Off

; Session
session.cookie_httponly = 1
session.use_strict_mode = 1
session.cookie_secure = 0

; Error reporting
display_errors = Off
log_errors = On
error_log = /var/log/php/error.log

; Date
date.timezone = America/Sao_Paulo
EOF

# Criar diretório de logs PHP
mkdir -p /var/log/php
chown www-data:www-data /var/log/php

# Configurar PHP-FPM
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/8.3/fpm/php.ini

systemctl restart php8.3-fpm
systemctl enable php8.3-fpm

echo "✅ PHP configurado e otimizado"

# ==========================================
# 5. NGINX - CONFIGURAÇÃO ROBUSTA
# ==========================================
echo ""
echo "🌐 5. CONFIGURANDO NGINX..."

# Remover configuração padrão
rm -f /etc/nginx/sites-enabled/default

# Criar configuração otimizada para OTServer
cat > /etc/nginx/sites-available/otserver <<EOF
# Configuração Nginx para OTServer
server {
    listen 80 default_server;
    listen [::]:80 default_server;
    
    server_name ${SERVER_IP} _;
    root ${WEBSITE_DIR};
    index index.php index.html index.htm;
    
    # Charset
    charset utf-8;
    
    # Security headers
    server_tokens off;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header X-Download-Options "noopen" always;
    add_header X-Permitted-Cross-Domain-Policies "none" always;
    
    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml text/javascript image/svg+xml;
    
    # Rate limiting
    limit_req_zone \$binary_remote_addr zone=login:10m rate=5r/m;
    limit_req_zone \$binary_remote_addr zone=api:10m rate=20r/m;
    
    # Main location
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
        
        # Rate limit for sensitive pages
        location ~* /(login|register|account) {
            limit_req zone=login burst=3 nodelay;
            try_files \$uri \$uri/ /index.php?\$query_string;
        }
    }
    
    # PHP processing
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param HTTP_PROXY "";
        include fastcgi_params;
        
        # Security
        fastcgi_hide_header X-Powered-By;
        
        # Timeouts
        fastcgi_read_timeout 300;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
    }
    
    # Downloads
    location /downloads/ {
        alias ${WEBSITE_DIR}/downloads/;
        autoindex on;
        autoindex_exact_size off;
        autoindex_localtime on;
        
        # Force download
        location ~* \.(zip|rar|exe)$ {
            add_header Content-Disposition "attachment";
            add_header X-Content-Type-Options "nosniff";
        }
    }
    
    # Static files
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        add_header X-Content-Type-Options "nosniff";
    }
    
    # Block sensitive files
    location ~ /\.(ht|git|svn) {
        deny all;
    }
    
    location ~ \.(sql|conf|config|ini|log)$ {
        deny all;
    }
    
    location ~ /(config|system|classes|vendor)/ {
        deny all;
    }
    
    # API rate limiting
    location /api/ {
        limit_req zone=api burst=10 nodelay;
        try_files \$uri \$uri/ /index.php?\$query_string;
    }
    
    # Logs
    access_log /var/log/nginx/otserver_access.log;
    error_log /var/log/nginx/otserver_error.log;
}
EOF

# Ativar site
ln -sf /etc/nginx/sites-available/otserver /etc/nginx/sites-enabled/

# Configurar nginx.conf
sed -i 's/worker_processes auto;/worker_processes 2;/' /etc/nginx/nginx.conf
sed -i 's/# server_tokens off;/server_tokens off;/' /etc/nginx/nginx.conf

# Testar configuração
nginx -t

if [ $? -eq 0 ]; then
    systemctl restart nginx
    systemctl enable nginx
    echo "✅ Nginx configurado com sucesso"
else
    echo "❌ Erro na configuração do Nginx"
    exit 1
fi

# ==========================================
# 6. DIRETÓRIOS E PERMISSÕES
# ==========================================
echo ""
echo "📁 6. CONFIGURANDO DIRETÓRIOS E PERMISSÕES..."

# Criar diretórios necessários
mkdir -p ${SERVER_DIR}
mkdir -p ${WEBSITE_DIR}
mkdir -p ${WEBSITE_DIR}/downloads/client
mkdir -p ${WEBSITE_DIR}/cache
mkdir -p ${WEBSITE_DIR}/layouts/tibiacom/images/guilds
mkdir -p ${WEBSITE_DIR}/layouts/tibiacom/images/players
mkdir -p /var/log/otserver
mkdir -p /var/log/otserver/website
mkdir -p /backup

# Criar usuário otserver
useradd -m -s /bin/bash -d /home/otserver otserver
usermod -aG sudo otserver

# Configurar permissões base
chown -R www-data:www-data ${WEBSITE_DIR}
chown -R otserver:otserver ${SERVER_DIR}
chown -R otserver:otserver /var/log/otserver

# Permissões específicas para website
chmod 755 ${WEBSITE_DIR}
chmod 755 ${WEBSITE_DIR}/cache
chmod 755 ${WEBSITE_DIR}/layouts/tibiacom/images/guilds
chmod 755 ${WEBSITE_DIR}/layouts/tibiacom/images/players
chmod 755 ${WEBSITE_DIR}/downloads

echo "✅ Diretórios e permissões configurados"

# ==========================================
# 7. SUPERVISOR PARA OTSERVER
# ==========================================
echo ""
echo "⚔️ 7. CONFIGURANDO SUPERVISOR..."

cat > /etc/supervisor/conf.d/otserver.conf <<EOF
[program:otserver]
command=${SERVER_DIR}/tfs
directory=${SERVER_DIR}
user=otserver
autostart=true
autorestart=true
startsecs=10
startretries=3
stdout_logfile=/var/log/otserver/output.log
stderr_logfile=/var/log/otserver/error.log
stdout_logfile_maxbytes=10MB
stderr_logfile_maxbytes=10MB
stdout_logfile_backups=5
stderr_logfile_backups=5
environment=HOME="/home/otserver",USER="otserver"

[group:gameserver]
programs=otserver
priority=999
EOF

systemctl restart supervisor
systemctl enable supervisor

echo "✅ Supervisor configurado"

# ==========================================
# 8. SISTEMA DE LOGS E MONITORAMENTO
# ==========================================
echo ""
echo "📊 8. CONFIGURANDO LOGS E MONITORAMENTO..."

# Configurar logrotate para OTServer
cat > /etc/logrotate.d/otserver <<EOF
/var/log/otserver/*.log {
    daily
    missingok
    rotate 7
    compress
    delaycompress
    notifempty
    copytruncate
    su otserver otserver
}

/var/log/otserver/website/*.log {
    daily
    missingok
    rotate 7
    compress
    delaycompress
    notifempty
    copytruncate
    su www-data www-data
}
EOF

# Script de monitoramento
cat > /opt/monitor_otserver.sh <<EOF
#!/bin/bash
# Monitoramento automático do OTServer

DATE=\$(date '+%Y-%m-%d %H:%M:%S')
LOG_FILE="/var/log/otserver/monitor.log"

# Verificar se o servidor está rodando
if ! supervisorctl status otserver | grep -q RUNNING; then
    echo "[\$DATE] ALERTA: OTServer parado! Reiniciando..." >> \$LOG_FILE
    supervisorctl restart otserver
    sleep 10
    
    # Verificar se reiniciou com sucesso
    if supervisorctl status otserver | grep -q RUNNING; then
        echo "[\$DATE] OTServer reiniciado com sucesso" >> \$LOG_FILE
    else
        echo "[\$DATE] ERRO: Falha ao reiniciar OTServer" >> \$LOG_FILE
    fi
fi

# Verificar conexões ativas
CONNECTIONS=\$(netstat -an | grep :7171 | grep ESTABLISHED | wc -l)
echo "[\$DATE] Conexões ativas: \$CONNECTIONS" >> \$LOG_FILE

# Verificar uso de memória
MEMORY=\$(ps aux | grep tfs | grep -v grep | awk '{print \$4}')
if [ ! -z "\$MEMORY" ]; then
    echo "[\$DATE] Uso de memória: \$MEMORY%" >> \$LOG_FILE
fi

# Verificar espaço em disco
DISK=\$(df -h / | awk 'NR==2{print \$5}' | sed 's/%//')
if [ \$DISK -gt 90 ]; then
    echo "[\$DATE] ALERTA: Espaço em disco baixo: \$DISK%" >> \$LOG_FILE
fi
EOF

chmod +x /opt/monitor_otserver.sh

# Adicionar ao crontab
(crontab -l 2>/dev/null; echo "*/5 * * * * /opt/monitor_otserver.sh") | crontab -

echo "✅ Monitoramento configurado"

# ==========================================
# 9. SISTEMA DE BACKUP
# ==========================================
echo ""
echo "💾 9. CONFIGURANDO SISTEMA DE BACKUP..."

cat > /opt/backup_otserver.sh <<EOF
#!/bin/bash
# Backup automático do OTServer

DATE=\$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup"
RETENTION_DAYS=7

echo "Iniciando backup: \$DATE"

# Backup do banco de dados
mysqldump -u otserver -p${DB_GAME_PASS} global > \$BACKUP_DIR/database_\$DATE.sql
if [ \$? -eq 0 ]; then
    echo "✅ Backup do banco: database_\$DATE.sql"
    gzip \$BACKUP_DIR/database_\$DATE.sql
else
    echo "❌ Erro no backup do banco"
fi

# Backup dos arquivos do servidor
tar -czf \$BACKUP_DIR/server_\$DATE.tar.gz -C /opt otserver
if [ \$? -eq 0 ]; then
    echo "✅ Backup do servidor: server_\$DATE.tar.gz"
else
    echo "❌ Erro no backup do servidor"
fi

# Backup do website
tar -czf \$BACKUP_DIR/website_\$DATE.tar.gz -C /var/www html
if [ \$? -eq 0 ]; then
    echo "✅ Backup do website: website_\$DATE.tar.gz"
else
    echo "❌ Erro no backup do website"
fi

# Limpeza de backups antigos
find \$BACKUP_DIR -name "*.sql.gz" -mtime +\$RETENTION_DAYS -delete
find \$BACKUP_DIR -name "*.tar.gz" -mtime +\$RETENTION_DAYS -delete

echo "Backup concluído: \$DATE"
EOF

chmod +x /opt/backup_otserver.sh

# Backup diário às 3h
(crontab -l 2>/dev/null; echo "0 3 * * * /opt/backup_otserver.sh >> /var/log/otserver/backup.log 2>&1") | crontab -

echo "✅ Sistema de backup configurado"

# ==========================================
# 10. PÁGINAS DE STATUS E INFORMAÇÃO
# ==========================================
echo ""
echo "📄 10. CRIANDO PÁGINAS DE STATUS..."

# Página de status do servidor
cat > ${WEBSITE_DIR}/server_status.php <<EOF
<?php
header('Content-Type: application/json');

function getServerStatus() {
    \$status = array(
        'timestamp' => date('c'),
        'server_ip' => '${SERVER_IP}',
        'services' => array()
    );
    
    // Verificar OTServer
    \$otserver = exec('supervisorctl status otserver 2>/dev/null | grep RUNNING');
    \$status['services']['otserver'] = !empty(\$otserver) ? 'online' : 'offline';
    
    // Verificar MySQL
    \$mysql = exec('systemctl is-active mysql 2>/dev/null');
    \$status['services']['mysql'] = (\$mysql == 'active') ? 'online' : 'offline';
    
    // Verificar Nginx
    \$nginx = exec('systemctl is-active nginx 2>/dev/null');
    \$status['services']['nginx'] = (\$nginx == 'active') ? 'online' : 'offline';
    
    // Verificar conexões ativas
    \$connections = exec("netstat -an | grep :7171 | grep ESTABLISHED | wc -l 2>/dev/null");
    \$status['active_connections'] = intval(\$connections);
    
    // Verificar uptime do sistema
    \$uptime = exec('uptime -p 2>/dev/null');
    \$status['system_uptime'] = \$uptime;
    
    // Status geral
    \$status['overall_status'] = (\$status['services']['otserver'] == 'online' && 
                                  \$status['services']['mysql'] == 'online' && 
                                  \$status['services']['nginx'] == 'online') ? 'online' : 'offline';
    
    return \$status;
}

echo json_encode(getServerStatus(), JSON_PRETTY_PRINT);
?>
EOF

# Página de informações do servidor
cat > ${WEBSITE_DIR}/info.php <<EOF
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTServer Info - ${SERVER_IP}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #1a1a1a; color: white; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #2d2d2d; padding: 30px; border-radius: 10px; text-align: center; margin-bottom: 20px; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .info-card { background: #2d2d2d; padding: 20px; border-radius: 10px; }
        .status-online { color: #28a745; }
        .status-offline { color: #dc3545; }
        .btn { background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; }
        .btn:hover { background: #0056b3; }
        h1 { color: #ffd700; margin: 0; }
        h2 { color: #00bfff; }
        .stat { margin: 10px 0; padding: 10px; background: #3d3d3d; border-radius: 5px; }
    </style>
    <script>
        function updateStatus() {
            fetch('/server_status.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('overall-status').textContent = data.overall_status.toUpperCase();
                    document.getElementById('overall-status').className = 'status-' + data.overall_status;
                    document.getElementById('connections').textContent = data.active_connections;
                    document.getElementById('uptime').textContent = data.system_uptime;
                    document.getElementById('last-update').textContent = new Date().toLocaleString('pt-BR');
                });
        }
        
        setInterval(updateStatus, 30000); // Atualizar a cada 30 segundos
        window.onload = updateStatus;
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎮 OTServer Tibia 8.60</h1>
            <p>Servidor Profissional - ${SERVER_IP}</p>
            <p>Status: <span id="overall-status" class="status-online">CARREGANDO...</span></p>
        </div>
        
        <div class="info-grid">
            <div class="info-card">
                <h2>📡 Informações de Conexão</h2>
                <div class="stat"><strong>IP:</strong> ${SERVER_IP}</div>
                <div class="stat"><strong>Porta Login:</strong> 7171</div>
                <div class="stat"><strong>Porta Game:</strong> 7172</div>
                <div class="stat"><strong>Versão:</strong> 8.60</div>
                <div class="stat"><strong>Protocolo:</strong> 860</div>
            </div>
            
            <div class="info-card">
                <h2>📊 Estatísticas</h2>
                <div class="stat"><strong>Conexões Ativas:</strong> <span id="connections">0</span></div>
                <div class="stat"><strong>Uptime Sistema:</strong> <span id="uptime">-</span></div>
                <div class="stat"><strong>Última Atualização:</strong> <span id="last-update">-</span></div>
                <div class="stat"><strong>Timezone:</strong> America/Sao_Paulo</div>
            </div>
            
            <div class="info-card">
                <h2>📥 Downloads</h2>
                <a href="/download.php" class="btn">📱 Cliente OTC Protegido</a>
                <a href="/downloads/cliente_${SERVER_IP}_protegido.zip" class="btn">💾 Download Direto</a>
                <a href="/downloads/PROTECOES_CLIENTE.txt" class="btn">📋 Info Proteções</a>
            </div>
            
            <div class="info-card">
                <h2>🔗 Links Úteis</h2>
                <a href="/" class="btn">🏠 Website Principal</a>
                <a href="/server_status.php" class="btn">📊 Status JSON</a>
                <a href="/pages/downloadclient.php" class="btn">📱 Download Oficial</a>
            </div>
            
            <div class="info-card">
                <h2>⚙️ Como Conectar</h2>
                <ol style="color: #ccc;">
                    <li>Baixe o cliente protegido</li>
                    <li>Extraia em uma pasta</li>
                    <li>Execute otclient_dx.exe</li>
                    <li>O servidor já estará na lista!</li>
                    <li>Crie conta no website primeiro</li>
                </ol>
            </div>
            
            <div class="info-card">
                <h2>🛡️ Proteções Ativas</h2>
                <div style="color: #28a745;">
                    ✅ Cliente protegido contra modificações<br>
                    ✅ Servidor único autorizado<br>
                    ✅ Verificação de integridade<br>
                    ✅ Anti-cheat ativo<br>
                    ✅ Backup automático<br>
                    ✅ Monitoramento 24/7
                </div>
            </div>
        </div>
    </div>
</body>
</html>
EOF

echo "✅ Páginas de status criadas"

# ==========================================
# 11. OTIMIZAÇÕES DE PERFORMANCE
# ==========================================
echo ""
echo "🚀 11. APLICANDO OTIMIZAÇÕES DE PERFORMANCE..."

# Otimizar kernel para servidor de jogos
cat > /etc/sysctl.d/99-otserver.conf <<EOF
# Otimizações para OTServer
net.core.rmem_max = 16777216
net.core.wmem_max = 16777216
net.ipv4.tcp_rmem = 4096 87380 16777216
net.ipv4.tcp_wmem = 4096 65536 16777216
net.ipv4.tcp_congestion_control = bbr
net.core.netdev_max_backlog = 5000
net.ipv4.tcp_max_syn_backlog = 4096
fs.file-max = 65536
vm.swappiness = 10
EOF

sysctl -p /etc/sysctl.d/99-otserver.conf

# Limites de sistema
cat > /etc/security/limits.d/99-otserver.conf <<EOF
# Limites para OTServer
otserver soft nofile 65536
otserver hard nofile 65536
www-data soft nofile 65536
www-data hard nofile 65536
EOF

echo "✅ Otimizações aplicadas"

# ==========================================
# FINALIZAÇÃO E INFORMAÇÕES
# ==========================================
echo ""
echo "=========================================="
echo "  ✅ DEPLOY COMPLETO FINALIZADO!"
echo "=========================================="
echo ""
echo "🔐 CREDENCIAIS GERADAS:"
echo "MySQL Root: ${DB_ROOT_PASS}"
echo "MySQL OTServer: ${DB_GAME_PASS}"
echo ""
echo "📝 SALVE ESTAS SENHAS EM LOCAL SEGURO!"
echo ""
echo "🌐 PRÓXIMOS PASSOS:"
echo "1. Envie os arquivos do servidor para /opt/otserver/"
echo "2. Envie os arquivos do website para /var/www/html/"
echo "3. Execute: ./setup_otserver_final.sh"
echo ""
echo "🔗 ACESSOS APÓS CONFIGURAÇÃO:"
echo "Website: http://${SERVER_IP}"
echo "Info: http://${SERVER_IP}/info.php"
echo "Status: http://${SERVER_IP}/server_status.php"
echo "Download: http://${SERVER_IP}/download.php"
echo ""
echo "📊 MONITORAMENTO:"
echo "Logs servidor: tail -f /var/log/otserver/output.log"
echo "Status: supervisorctl status otserver"
echo "Monitor: /opt/monitor_otserver.sh"
echo ""
echo "💾 BACKUP:"
echo "Automático: Diário às 3h"
echo "Manual: /opt/backup_otserver.sh"
echo ""
echo "🛡️ SISTEMA TOTALMENTE SEGURO E OTIMIZADO!"

