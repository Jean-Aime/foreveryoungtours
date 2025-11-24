<?php
// Multi-language support system

function getCurrentLanguage() {
    return $_SESSION['language'] ?? 'en';
}

function setLanguage($lang_code) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT code FROM languages WHERE code = ? AND is_active = 1");
    $stmt->execute([$lang_code]);
    if ($stmt->fetch()) {
        $_SESSION['language'] = $lang_code;
        return true;
    }
    return false;
}

function translate($key, $default = null) {
    global $pdo;
    static $cache = [];
    
    $lang = getCurrentLanguage();
    $cache_key = $lang . '_' . $key;
    
    if (isset($cache[$cache_key])) {
        return $cache[$cache_key];
    }
    
    $stmt = $pdo->prepare("SELECT translation_value FROM translations WHERE language_code = ? AND translation_key = ?");
    $stmt->execute([$lang, $key]);
    $result = $stmt->fetch();
    
    $value = $result ? $result['translation_value'] : ($default ?? $key);
    $cache[$cache_key] = $value;
    return $value;
}

function t($key, $default = null) {
    return translate($key, $default);
}

function getAvailableLanguages() {
    global $pdo;
    return $pdo->query("SELECT * FROM languages WHERE is_active = 1 ORDER BY is_default DESC, name")->fetchAll();
}
