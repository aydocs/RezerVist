import { test, expect } from '@playwright/test';

const roles = [
    { name: 'Admin', email: 'ayberk@rezervist.com', pass: 'ayberk123', url: '/admin' },
    { name: 'Vendor', email: 'owner1@test.com', pass: 'password', url: '/vendor/dashboard' },
    { name: 'Customer', email: 'ahmet@test.com', pass: 'password', url: '/profile' }
];

test.describe('A to Z System Audit', () => {

    test('Step 1: Public Route Audit', async ({ page }) => {
        const routes = ['/', '/search', '/contact'];
        for (const route of routes) {
            console.log(`Auditing Public Route: ${route}`);
            await page.goto(`${route}`);
            await expect(page).not.toHaveTitle(/500/);
            await page.screenshot({ path: `test-results/screenshots/public-${route.replace(/\//g, 'home')}.png` });
        }
    });

    for (const role of roles) {
        test(`Audit Role: ${role.name}`, async ({ page }) => {
            console.log(`Starting Audit for ${role.name}`);
            await page.goto('/login');

            // Handle Cookie Banner
            const cookieBtn = page.locator('button:has-text("Kabul Et"), button:has-text("Kabul")').first();
            if (await cookieBtn.isVisible()) await cookieBtn.click();

            await page.fill('input[name="email"]', role.email);
            await page.fill('input[name="password"]', role.pass);
            await page.click('button[type="submit"]');

            await page.waitForURL('**');
            await page.goto(`${role.url}`);

            // Basic functional checks
            await expect(page.locator('body')).not.toContainText(['Error 500', 'Server Error', 'SQLSTATE']);

            // Take a "Proof of Health" screenshot
            await page.screenshot({ path: `test-results/screenshots/atoz-${role.name.toLowerCase()}.png`, fullPage: true });

            console.log(`Audit Complete for ${role.name}`);
        });
    }

    test('Step 5: Search & Interaction Flow', async ({ page }) => {
        await page.goto('/');

        // Handle Cookie Banner
        const cookieBtn = page.locator('button:has-text("Kabul Et"), button:has-text("Kabul")').first();
        if (await cookieBtn.isVisible()) await cookieBtn.click();

        await page.fill('input[placeholder*="Restoran"]', 'Burger');
        await page.click('button:has-text("Ara")');

        await page.waitForTimeout(2000);
        await expect(page.locator('body')).not.toContainText('500');
        await page.screenshot({ path: 'test-results/screenshots/atoz-search-results.png' });

        // Click first business result if any
        const firstResult = page.locator('.business-card, a[href*="/isletme/"]').first();
        if (await firstResult.isVisible()) {
            await firstResult.click();
            await page.waitForTimeout(2000);
            await page.screenshot({ path: 'test-results/screenshots/atoz-business-detail.png' });
        }
    });
});
