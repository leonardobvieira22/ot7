#!/bin/bash
# DEPLOY COMPLETO VIA GITHUB - OTServer Tibia 8.60
# Clone do projeto completo + deploy automatizado

echo "=========================================="
echo "  🚀 DEPLOY VIA GITHUB - ULTRA RÁPIDO"
echo "  📁 Clone completo + deploy automatizado"
echo "=========================================="
echo ""

# Variáveis
SERVER_IP="181.215.45.238"
GITHUB_REPO="https://github.com/leonardobvieira22/ot7.git"
PROJECT_DIR="/root/ot7"

echo "📥 Clonando projeto completo do GitHub..."

# Remover diretório se existir
if [ -d "$PROJECT_DIR" ]; then
    echo "Removendo clone anterior..."
    rm -rf "$PROJECT_DIR"
fi

# Clonar repositório completo
cd /root
git clone "$GITHUB_REPO"

if [ $? -eq 0 ]; then
    echo "✅ Projeto clonado com sucesso!"
    cd "$PROJECT_DIR"
    
    # Verificar se arquivos essenciais existem
    if [ -f "deploy_final_completo.sh" ] && [ -d "Servidor 8.60 Atualizado" ]; then
        echo "✅ Arquivos do projeto verificados"
        
        # Dar permissões aos scripts
        chmod +x *.sh
        
        echo ""
        echo "🏗️ Executando deploy de infraestrutura..."
        ./deploy_final_completo.sh
        
        if [ $? -eq 0 ]; then
            echo ""
            echo "📁 Movendo arquivos do servidor..."
            
            # Mover arquivos automaticamente
            echo "Copiando arquivos do servidor..."
            cp -r "Servidor 8.60 Atualizado/Global/"* /opt/otserver/ 2>/dev/null
            
            echo "Copiando arquivos do website..."
            cp -r "Servidor 8.60 Atualizado/htdocs/"* /var/www/html/ 2>/dev/null
            
            echo "Copiando cliente..."
            mkdir -p /var/www/html/downloads/client
            cp -r "Servidor 8.60 Atualizado/solera-otclientv8/"* /var/www/html/downloads/client/ 2>/dev/null
            
            echo "✅ Arquivos movidos com sucesso!"
            
            echo ""
            echo "⚙️ Executando configuração final..."
            ./setup_otserver_final.sh
            
            if [ $? -eq 0 ]; then
                echo ""
                echo "=========================================="
                echo "  🎉 DEPLOY COMPLETO FINALIZADO!"
                echo "=========================================="
                echo ""
                echo "🌐 ACESSOS:"
                echo "Website: http://${SERVER_IP}"
                echo "Info: http://${SERVER_IP}/info.php"
                echo "Download: http://${SERVER_IP}/download.php"
                echo ""
                echo "🎮 JOGO:"
                echo "IP: ${SERVER_IP}:7171"
                echo "Conta: admin/admin"
                echo ""
                echo "🎯 SEU OTSERVER ESTÁ ONLINE!"
            else
                echo "❌ Erro na configuração final"
            fi
        else
            echo "❌ Erro no deploy de infraestrutura"
        fi
    else
        echo "❌ Arquivos do projeto não encontrados"
        echo "Verifique se o upload para GitHub foi completo"
    fi
else
    echo "❌ Erro ao clonar repositório"
    echo "Verifique se o repositório está público e acessível"
fi
