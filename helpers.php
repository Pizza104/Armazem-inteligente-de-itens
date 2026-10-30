<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_secret(): string
{
    $secret = getenv('CSRF_SECRET');
    if (is_string($secret) && strlen($secret) >= 32) {
        return $secret;
    }

    if (getenv('VERCEL') !== false || getenv('APP_ENV') === 'production') {
        throw new RuntimeException('Configure CSRF_SECRET com pelo menos 32 caracteres nas variáveis de ambiente.');
    }

    return hash('sha256', 'somente-desenvolvimento-local|' . __DIR__);
}

function secure_cookie_options(int $expires): array
{
    $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https'
        || getenv('VERCEL') !== false;

    return [
        'expires' => $expires,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ];
}

function csrf_cookie_is_valid(string $cookie): bool
{
    $parts = explode('.', $cookie, 2);
    if (count($parts) !== 2 || !preg_match('/^[a-f0-9]{64}$/', $parts[0])) {
        return false;
    }

    return hash_equals(hash_hmac('sha256', $parts[0], csrf_secret()), $parts[1]);
}

function csrf_token(): string
{
    $cookie = (string) ($_COOKIE['_csrf'] ?? '');
    if (csrf_cookie_is_valid($cookie)) {
        return $cookie;
    }

    $nonce = bin2hex(random_bytes(32));
    $cookie = $nonce . '.' . hash_hmac('sha256', $nonce, csrf_secret());
    setcookie('_csrf', $cookie, secure_cookie_options(time() + 7200));
    $_COOKIE['_csrf'] = $cookie;
    return $cookie;
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $submitted = (string) ($_POST['_csrf'] ?? '');
    $cookie = (string) ($_COOKIE['_csrf'] ?? '');
    if ($submitted === '' || !csrf_cookie_is_valid($cookie) || !hash_equals($cookie, $submitted)) {
        throw new RuntimeException('Sua sessão expirou. Atualize a página e tente novamente.');
    }
}

function set_flash(string $type, string $message): void
{
    $payload = json_encode([
        'type' => $type,
        'message' => $message,
        'expires' => time() + 120,
    ], JSON_THROW_ON_ERROR);
    $encoded = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
    $cookie = $encoded . '.' . hash_hmac('sha256', $encoded, csrf_secret());
    setcookie('_flash', $cookie, secure_cookie_options(time() + 120));
    $_COOKIE['_flash'] = $cookie;
}

function take_flash(): ?array
{
    $cookie = (string) ($_COOKIE['_flash'] ?? '');
    setcookie('_flash', '', secure_cookie_options(time() - 3600));
    unset($_COOKIE['_flash']);

    $parts = explode('.', $cookie, 2);
    if (count($parts) !== 2 || !hash_equals(hash_hmac('sha256', $parts[0], csrf_secret()), $parts[1])) {
        return null;
    }

    $decoded = base64_decode(strtr($parts[0], '-_', '+/'), true);
    if (!is_string($decoded)) {
        return null;
    }

    try {
        $flash = json_decode($decoded, true, 8, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        return null;
    }

    if (!is_array($flash)
        || !in_array($flash['type'] ?? '', ['success', 'danger', 'warning', 'info'], true)
        || !is_string($flash['message'] ?? null)
        || (int) ($flash['expires'] ?? 0) < time()) {
        return null;
    }

    return $flash;
}

function required_text(array $source, string $key, string $label, int $maxLength): string
{
    $value = trim((string) ($source[$key] ?? ''));
    if ($value === '') {
        throw new InvalidArgumentException("O campo {$label} é obrigatório.");
    }
    preg_match_all('/./us', $value, $characters);
    if (count($characters[0] ?? []) > $maxLength) {
        throw new InvalidArgumentException("O campo {$label} deve ter no máximo {$maxLength} caracteres.");
    }
    return $value;
}

function positive_integer(array $source, string $key, string $label, bool $allowZero = false): int
{
    $value = filter_var($source[$key] ?? null, FILTER_VALIDATE_INT);
    $minimum = $allowZero ? 0 : 1;
    if ($value === false || $value < $minimum) {
        throw new InvalidArgumentException("Informe um valor válido para {$label}.");
    }
    return $value;
}

function money_value(array $source, string $key, string $label): string
{
    $value = trim((string) ($source[$key] ?? '0'));
    if ($value === '') {
        $value = '0';
    }
    if (!preg_match('/^\d{1,8}(?:\.\d{1,2})?$/', $value)) {
        throw new InvalidArgumentException("Informe um valor válido para {$label} (ex.: 12.50).");
    }
    return number_format((float) $value, 2, '.', '');
}
