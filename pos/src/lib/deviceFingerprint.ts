/**
 * Generate device fingerprint from browser data
 */
export function generateDeviceFingerprint(): string {
    const components = [
        navigator.userAgent,
        navigator.language,
        navigator.platform,
        screen.width.toString(),
        screen.height.toString(),
        screen.colorDepth.toString(),
        new Date().getTimezoneOffset().toString(),
        navigator.hardwareConcurrency?.toString() || '0',
        (navigator as any).deviceMemory?.toString() || '0'
    ];

    const fingerprint = components.join('|');

    // Create a simple hash
    let hash = 0;
    for (let i = 0; i < fingerprint.length; i++) {
        const char = fingerprint.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash; // Convert to 32bit integer
    }

    return Math.abs(hash).toString(36);
}

export default { generateDeviceFingerprint };
