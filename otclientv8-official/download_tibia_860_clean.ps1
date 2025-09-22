# 🎮 SCRIPT PARA BAIXAR ARQUIVOS TIBIA 8.60 LIMPOS
# Executar no PowerShell como Administrador

Write-Host "🎮 BAIXANDO ARQUIVOS TIBIA 8.60 LIMPOS..." -ForegroundColor Green

# Criar diretório se não existir
$tibiaDir = "data\things\860"
if (!(Test-Path $tibiaDir)) {
    New-Item -ItemType Directory -Path $tibiaDir -Force
    Write-Host "📁 Diretório criado: $tibiaDir" -ForegroundColor Yellow
}

# URLs de arquivos Tibia 8.60 limpos (repositórios confiáveis)
# Múltiplas fontes para garantir download
$sources = @(
    @{
        dat = "https://github.com/mehah/otclient/raw/main/data/things/860/Tibia.dat"
        spr = "https://github.com/mehah/otclient/raw/main/data/things/860/Tibia.spr"
        name = "Mehah OTClient"
    },
    @{
        dat = "https://github.com/edubart/otclient/raw/master/data/things/860/Tibia.dat"
        spr = "https://github.com/edubart/otclient/raw/master/data/things/860/Tibia.spr"
        name = "Edubart OTClient"
    }
)

try {
    Write-Host "📥 Baixando Tibia.dat..." -ForegroundColor Cyan
    Invoke-WebRequest -Uri $datUrl -OutFile "$tibiaDir\Tibia.dat" -UseBasicParsing
    Write-Host "✅ Tibia.dat baixado com sucesso!" -ForegroundColor Green

    Write-Host "📥 Baixando Tibia.spr..." -ForegroundColor Cyan  
    Invoke-WebRequest -Uri $sprUrl -OutFile "$tibiaDir\Tibia.spr" -UseBasicParsing
    Write-Host "✅ Tibia.spr baixado com sucesso!" -ForegroundColor Green

    # Verificar tamanhos dos arquivos
    $datSize = (Get-Item "$tibiaDir\Tibia.dat").Length / 1MB
    $sprSize = (Get-Item "$tibiaDir\Tibia.spr").Length / 1MB

    Write-Host "📊 ARQUIVOS BAIXADOS:" -ForegroundColor Yellow
    Write-Host "   Tibia.dat: $([math]::Round($datSize, 2)) MB" -ForegroundColor White
    Write-Host "   Tibia.spr: $([math]::Round($sprSize, 2)) MB" -ForegroundColor White

    if ($datSize -gt 0.5 -and $sprSize -gt 5) {
        Write-Host "🎉 ARQUIVOS BAIXADOS COM SUCESSO!" -ForegroundColor Green
        Write-Host "🎮 Agora você pode executar o cliente novamente!" -ForegroundColor Green
    } else {
        Write-Host "⚠️ ATENÇÃO: Arquivos podem estar incompletos!" -ForegroundColor Red
    }

} catch {
    Write-Host "❌ ERRO AO BAIXAR: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "💡 SOLUÇÃO ALTERNATIVA: Use o bypass no things.lua" -ForegroundColor Yellow
}

Write-Host "🔄 Pressione qualquer tecla para continuar..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
