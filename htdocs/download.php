<?php
require_once 'config/config.php';

class SecureDownloader {
    private $clientPath = '../solera-otclientv8/';
    private $checksums = [];
    private $allowedFiles = [
        'otclient_dx.exe' => 'application/x-msdownload',
        'otclient_gl.exe' => 'application/x-msdownload',
        'data.zip' => 'application/zip'
    ];
    
    public function __construct() {
        // Gera checksums dos arquivos permitidos
        foreach ($this->allowedFiles as $file => $mime) {
            if (file_exists($this->clientPath . $file)) {
                $this->checksums[$file] = hash_file('sha256', $this->clientPath . $file);
            }
        }
    }
    
    public function verifyFile($filename) {
        if (!isset($this->allowedFiles[$filename])) {
            return false;
        }
        
        $filepath = $this->clientPath . $filename;
        if (!file_exists($filepath)) {
            return false;
        }
        
        // Verifica checksum
        $currentChecksum = hash_file('sha256', $filepath);
        return $currentChecksum === $this->checksums[$filename];
    }
    
    public function downloadFile($filename) {
        if (!$this->verifyFile($filename)) {
            http_response_code(404);
            die('File not found or corrupted');
        }
        
        $filepath = $this->clientPath . $filename;
        $filesize = filesize($filepath);
        
        // Headers para download seguro
        header('Content-Type: ' . $this->allowedFiles[$filename]);
        header('Content-Length: ' . $filesize);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('Content-Security-Policy: default-src \'none\'');
        header('X-Checksum: ' . $this->checksums[$filename]);
        
        // Rate limiting
        session_start();
        $now = time();
        if (isset($_SESSION['last_download']) && ($now - $_SESSION['last_download']) < 60) {
            http_response_code(429);
            die('Please wait before downloading again');
        }
        $_SESSION['last_download'] = $now;
        
        // Log download
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s');
        $log = "$date - IP: $ip downloaded $filename\n";
        file_put_contents('../logs/downloads.log', $log, FILE_APPEND);
        
        // Envia arquivo em chunks para arquivos grandes
        if ($filesize > 1024 * 1024) { // Se maior que 1MB
            $handle = fopen($filepath, 'rb');
            while (!feof($handle)) {
                echo fread($handle, 1024 * 1024);
                flush();
            }
            fclose($handle);
        } else {
            readfile($filepath);
        }
    }
}

// Uso
if (isset($_GET['file'])) {
    $downloader = new SecureDownloader();
    $downloader->downloadFile($_GET['file']);
} else {
    // Página de download
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Download Client - OTServer</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            .download-box {
                border: 1px solid #ddd;
                padding: 20px;
                margin: 20px 0;
                border-radius: 5px;
            }
            .download-button {
                display: inline-block;
                padding: 10px 20px;
                background: #4CAF50;
                color: white;
                text-decoration: none;
                border-radius: 3px;
                margin: 10px 0;
            }
            .requirements {
                background: #f9f9f9;
                padding: 15px;
                margin: 15px 0;
            }
        </style>
    </head>
    <body>
        <h1>Download Client</h1>
        
        <div class="download-box">
            <h2>Windows Client</h2>
            <p>Escolha a versão adequada para seu sistema:</p>
            <a href="?file=otclient_dx.exe" class="download-button">Download DirectX Version</a>
            <a href="?file=otclient_gl.exe" class="download-button">Download OpenGL Version</a>
        </div>
        
        <div class="requirements">
            <h3>Requisitos Mínimos:</h3>
            <ul>
                <li>Windows 7 ou superior</li>
                <li>2GB RAM</li>
                <li>DirectX 9.0c ou OpenGL 2.1</li>
                <li>Conexão com a Internet</li>
            </ul>
        </div>
        
        <div class="download-box">
            <h2>Instruções de Instalação</h2>
            <ol>
                <li>Faça o download da versão adequada para seu sistema</li>
                <li>Execute o arquivo baixado</li>
                <li>Selecione o local de instalação</li>
                <li>Aguarde a conclusão da instalação</li>
                <li>Execute o client e divirta-se!</li>
            </ol>
        </div>
    </body>
    </html>
    <?php
}
?>
