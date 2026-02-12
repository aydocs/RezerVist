import { app, BrowserWindow, ipcMain, shell } from 'electron'
import { autoUpdater } from 'electron-updater'
import path from 'node:path'
import { fileURLToPath } from 'node:url'

const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)

// The built directory structure
//
// ├─┬─ dist
// │ ├─index.html
// │ ├─icon.ico
// │ ├─ assets
// │ └─ ...
// ├─┬─ dist-electron
// │ ├─ main.js
// │ └─ preload.js
//
process.env.DIST = path.join(__dirname, '../dist')
process.env.VITE_PUBLIC = app.isPackaged ? process.env.DIST : path.join(__dirname, '../public')

let win: BrowserWindow | null

// 🚧 Use ['ENV_NAME'] avoid vite:define plugin - Vite@2.x
const VITE_DEV_SERVER_URL = process.env['VITE_DEV_SERVER_URL']

function createWindow() {
    win = new BrowserWindow({
        width: 1200,
        height: 800,
        icon: path.join(process.env.VITE_PUBLIC, 'electron-vite.svg'),
        webPreferences: {
            preload: path.join(__dirname, 'preload.js'),
        },
        autoHideMenuBar: true, // Adisyo style (clean)
        trafficLightPosition: { x: 15, y: 15 }, // macOS polish
    })

    // Full screen for POS feel
    win.maximize()

    if (VITE_DEV_SERVER_URL) {
        win.loadURL(VITE_DEV_SERVER_URL)
    } else {
        // win.loadFile('dist/index.html')
        win.loadFile(path.join(process.env.DIST, 'index.html'))
    }

    // Open urls in the user's browser
    win.webContents.setWindowOpenHandler((details) => {
        const { url } = details
        if (url.startsWith('https:') || url.startsWith('http:')) {
            shell.openExternal(url)
        }
        return { action: 'deny' }
    })

    // --- Auto-Updater Logic ---
    autoUpdater.autoDownload = true;
    autoUpdater.autoInstallOnAppQuit = true;

    // Periodic check (every 4 hours)
    setInterval(() => {
        autoUpdater.checkForUpdates();
    }, 4 * 60 * 60 * 1000);

    autoUpdater.on('checking-for-update', () => {
        win?.webContents.send('update:status', { type: 'checking' });
    });

    autoUpdater.on('update-available', (info) => {
        win?.webContents.send('update:status', { type: 'available', version: info.version });
    });

    autoUpdater.on('update-not-available', () => {
        win?.webContents.send('update:status', { type: 'not-available' });
    });

    autoUpdater.on('error', (err) => {
        win?.webContents.send('update:status', { type: 'error', message: err.message });
    });

    autoUpdater.on('download-progress', (progressObj) => {
        win?.webContents.send('update:status', {
            type: 'downloading',
            percent: progressObj.percent,
            speed: progressObj.bytesPerSecond
        });
    });

    autoUpdater.on('update-downloaded', (info) => {
        win?.webContents.send('update:status', { type: 'downloaded', version: info.version });
    });

    // Initial check
    setTimeout(() => {
        autoUpdater.checkForUpdates();
    }, 5000);
}

// IPC Handlers
ipcMain.on('update:install', () => {
    autoUpdater.quitAndInstall();
});

// Quit when all windows are closed, except on macOS. There, it's common
// for applications and their menu bar to stay active until the user quits
// explicitly with Cmd + Q.
app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') {
        app.quit()
    }
})

app.on('activate', () => {
    // On OS X it's common to re-create a window in the app when the
    // dock icon is clicked and there are no other windows open.
    if (BrowserWindow.getAllWindows().length === 0) {
        createWindow()
    }
})

app.whenReady().then(createWindow)
