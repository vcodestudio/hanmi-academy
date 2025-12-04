/*
  Dev runner:
  - Loads .env for DEV_URL
  - Spawns webpack in watch mode
  - Launches Playwright Chromium pointed to DEV_URL
  - Watches php/js/less/css changes and reloads the page
*/
const { spawn } = require('child_process');
const http = require('http');
const https = require('https');
const chokidar = require('chokidar');
const { chromium } = require('playwright');
require('dotenv').config();

const DEV_URL = process.env.DEV_URL || process.env.SITE_URL || 'http://localhost:8000/';
const DEV_WAIT_TIMEOUT_MS = parseInt(process.env.DEV_WAIT_TIMEOUT_MS || '120000', 10);
const DEV_WAIT_INTERVAL_MS = parseInt(process.env.DEV_WAIT_INTERVAL_MS || '1000', 10);
if (!DEV_URL) {
  console.error('[dev] .env missing DEV_URL (or SITE_URL). Please set it.');
  process.exit(1);
}

let webpackProc;
let browser;
let page;
let reloading = false;
let reloadTimer = null;

async function ensureBrowsers() {
  return new Promise((resolve) => {
    const cmd = process.platform === 'win32' ? 'npx.cmd' : 'npx';
    const p = spawn(cmd, ['playwright', 'install', 'chromium'], { stdio: 'inherit' });
    p.on('close', () => resolve());
    p.on('error', () => resolve());
  });
}

function ping(url, timeout = 2000) {
  return new Promise((resolve) => {
    let u;
    try { u = new URL(url); } catch { resolve(false); return; }
    const lib = u.protocol === 'https:' ? https : http;
    const options = {
      method: 'HEAD',
      hostname: u.hostname,
      port: u.port || (u.protocol === 'https:' ? 443 : 80),
      path: u.pathname || '/',
      timeout,
    };
    const req = lib.request(options, (res) => {
      const ok = res.statusCode && res.statusCode < 500;
      res.resume();
      resolve(!!ok);
    });
    req.on('timeout', () => { req.destroy(); resolve(false); });
    req.on('error', () => resolve(false));
    req.end();
  });
}

async function waitForServer(url, maxMs = DEV_WAIT_TIMEOUT_MS, interval = DEV_WAIT_INTERVAL_MS) {
  const start = Date.now();
  while (Date.now() - start < maxMs) {
    if (await ping(url)) return true;
    await new Promise((r) => setTimeout(r, interval));
  }
  return false;
}

async function start() {
  console.log(`[dev] Opening: ${DEV_URL}`);

  // Start webpack watch
  webpackProc = spawn(
    process.platform === 'win32' ? 'npx.cmd' : 'npx',
    ['webpack', '--watch', '--mode=development', '--config', './webpack.config.js'],
    { stdio: 'inherit' }
  );

  // Ensure Playwright browsers are installed (no-op if already)
  await ensureBrowsers();

  // Launch Playwright browser
  browser = await chromium.launch({ 
    headless: false,
    args: ['--start-maximized']
  });
  const context = await browser.newContext({
    viewport: null  // Use full window size instead of fixed viewport
  });
  page = await context.newPage();

  const ready = await waitForServer(DEV_URL);
  if (ready) {
    await page.goto(DEV_URL, { waitUntil: 'load' });
  } else {
    console.warn('[dev] Server not reachable yet. Will retry navigating in background...');
    const retry = setInterval(async () => {
      try {
        if (await ping(DEV_URL)) {
          clearInterval(retry);
          if (page && !page.isClosed()) {
            await page.goto(DEV_URL, { waitUntil: 'load' });
          }
        }
      } catch {}
    }, DEV_WAIT_INTERVAL_MS);
  }

  // Watch files
  const watcher = chokidar.watch([
    '**/*.php',
    'src/**/*.js',
    'src/**/*.less',
    'src/**/*.css',
    'src/js/dist/**/*.{css,js}',
  ], {
    ignored: ['node_modules/**', '.git/**'],
    ignoreInitial: true,
  });

  const scheduleReload = () => {
    if (reloadTimer) clearTimeout(reloadTimer);
    reloadTimer = setTimeout(async () => {
      if (reloading) return;
      reloading = true;
      try {
        if (page && !page.isClosed()) {
          console.log('[dev] Reloading...');
          await page.reload({ waitUntil: 'domcontentloaded' });
        }
      } catch (e) {
        console.error('[dev] Reload error:', e);
      } finally {
        reloading = false;
      }
    }, 150);
  };

  watcher.on('change', scheduleReload)
         .on('add', scheduleReload)
         .on('unlink', scheduleReload);

  const cleanup = async () => {
    console.log('\n[dev] Shutting down...');
    try { watcher.close(); } catch {}
    try { if (webpackProc) webpackProc.kill(); } catch {}
    try { if (browser) await browser.close(); } catch {}
    process.exit(0);
  };

  process.on('SIGINT', cleanup);
  process.on('SIGTERM', cleanup);
}

start().catch((err) => {
  console.error('[dev] Failed to start:', err);
  process.exit(1);
});
