import CryptoJS from 'crypto-js';

/**
 * Secure storage utility for encrypting sensitive data in localStorage
 */
class SecureStorage {
    /**
     * Generate encryption key from device fingerprint
     * This makes the encrypted data device-specific
     */
    private static getEncryptionKey(): string {
        const fingerprint = [
            navigator.userAgent,
            screen.width.toString(),
            screen.height.toString(),
            screen.colorDepth.toString(),
            new Date().getTimezoneOffset().toString(),
            navigator.language,
            navigator.platform
        ].join('|');

        // Hash the fingerprint to create a consistent key
        return CryptoJS.SHA256(fingerprint).toString();
    }

    /**
     * Encrypt and store a token
     */
    static setToken(token: string): void {
        try {
            const key = this.getEncryptionKey();
            const encrypted = CryptoJS.AES.encrypt(token, key).toString();
            sessionStorage.setItem('auth_token_enc', encrypted);
            sessionStorage.setItem('auth_token_ts', Date.now().toString());
        } catch (error) {
            console.error('Failed to encrypt token:', error);
            // Fallback to plain storage if encryption fails
            sessionStorage.setItem('auth_token', token);
        }
    }

    /**
     * Decrypt and retrieve a token
     */
    static getToken(): string | null {
        try {
            const encrypted = sessionStorage.getItem('auth_token_enc');
            if (!encrypted) {
                // Fallback to plain token if encrypted version doesn't exist
                return sessionStorage.getItem('auth_token');
            }

            const key = this.getEncryptionKey();
            const decrypted = CryptoJS.AES.decrypt(encrypted, key);
            const token = decrypted.toString(CryptoJS.enc.Utf8);

            if (!token) {
                // Decryption failed, clear and return null
                this.clearToken();
                return null;
            }

            return token;
        } catch (error) {
            console.error('Failed to decrypt token:', error);
            this.clearToken();
            return null;
        }
    }

    /**
     * Clear all token data
     */
    static clearToken(): void {
        sessionStorage.removeItem('auth_token_enc');
        sessionStorage.removeItem('auth_token_ts');
        sessionStorage.removeItem('auth_token'); // Clear fallback too
    }

    /**
     * Check if token exists and is valid
     */
    static hasValidToken(): boolean {
        return this.getToken() !== null;
    }

    /**
     * Get token age in milliseconds (for monitoring purposes only)
     */
    static getTokenAge(): number | null {
        const timestamp = sessionStorage.getItem('pos_token_ts');
        if (!timestamp) return null;
        return Date.now() - parseInt(timestamp);
    }

    /**
     * Store business name (encrypted)
     */
    static setBusinessName(name: string): void {
        try {
            const key = this.getEncryptionKey();
            const encrypted = CryptoJS.AES.encrypt(name, key).toString();
            localStorage.setItem('business_name_enc', encrypted);
        } catch (error) {
            console.error('Failed to encrypt business name:', error);
            localStorage.setItem('business_name', name);
        }
    }

    /**
     * Get business name (decrypted)
     */
    static getBusinessName(): string | null {
        try {
            const encrypted = localStorage.getItem('business_name_enc');
            if (!encrypted) {
                return localStorage.getItem('business_name');
            }

            const key = this.getEncryptionKey();
            const decrypted = CryptoJS.AES.decrypt(encrypted, key);
            return decrypted.toString(CryptoJS.enc.Utf8);
        } catch (error) {
            console.error('Failed to decrypt business name:', error);
            return localStorage.getItem('business_name');
        }
    }
}

export default SecureStorage;
