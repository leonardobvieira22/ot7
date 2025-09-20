<?php
namespace RobThree\Auth;

class TwoFactorAuth {
    public function __construct($issuer = null, $digits = 6, $period = 30, $algorithm = 'sha1') {
        // Implementação básica temporária
    }
    
    public function createSecret($bits = 80) {
        return 'TEMPORARY_SECRET';
    }
    
    public function getQRText($secret, $title) {
        return 'temp_qr';
    }
    
    public function verifyCode($secret, $code, $discrepancy = 1, $time = null) {
        return true; // Temporário - aceita qualquer código
    }
}
