import {resolve} from "path";
import {defineConfig} from 'vite';
import legacy from '@vitejs/plugin-legacy'

import {
  fluidComponentsPlugin,
  typo3Plugin
} from "vite-fluid-components";

export default defineConfig({
  appType: 'mpa',
  server: {
    origin: `https://${process.env.DDEV_HOSTNAME}:${process.env.VITE_DEV_PORT ?? 5173}`,
    port: process.env.VITE_DEV_PORT ?? 5173,
    hmr: {
      clientPort: process.env.VITE_DEV_PORT ?? 5173,
    },
    strictPort: true,
    open: false,
    host: true,
    watch: {
      followSymlinks: false,
      ignored: [
        '**/.ddev/**',
        '**/.idea/**',
        // '/var/www/html/private/**',
        '/var/www/html/public/**',
      ],
      disableGlobbing: false
    }
  },
  css: {
    devSourcemap: true,
    preprocessorOptions: {
      scss: { quietDeps: true }
    }
  },
  optimizeDeps: {
    force: true
  },
  base: './',
  build: {
    copyPublicDir: false,
    outDir: './extensions/site_package/Resources/Public/Assets/',
    manifest: true,
    sourcemap: true,
    cssCodeSplit: true,
    emptyOutDir: true,
    commonjsOptions: {
      transformMixedEsModules: true,
    },
    rollupOptions: {
      input: {
        'JavaScript/Main': resolve(__dirname, 'extensions/site_package/Resources/Private/JavaScript/Main.js'),
        'CKEditor': resolve(__dirname, 'extensions/site_package/Resources/Private/Scss/ckeditor.scss')
      },
      output: {
        // format: 'umd',
        // caching is done by typo3 via //<asset>?<timestamp>
        chunkFileNames: "[name].js",
        entryFileNames: "[name].js",
        assetFileNames: "[name][extname]",
      },
    }
  },
  resolve: {
    alias: [
      {
        find: /^~.+/,
        replacement: (val) => {
          return val.replace(/^~/, "");
        },
      },
    ],
  },
  plugins: [
    legacy({
      targets: ['defaults', 'not IE 11'],
    }),
    typo3Plugin({
      delay: 500,
      reloadOnChange: [
        resolve(__dirname, 'extensions/site_package/Resources/Private/Templates/**/*'),
        resolve(__dirname, 'extensions/site_package/Resources/Private/Layouts/**/*'),
        resolve(__dirname, 'extensions/site_package/Resources/Private/Partials/**/*'),
      ]
    }),
    fluidComponentsPlugin([
      {
        namespace: "Werkraum\\SitePackage\\Components",
        pathToComponents: resolve(__dirname, 'extensions/site_package/Resources/Private/Components/'),
        componentPattern: "**/*.{js,ts,tsx,jsx,css,scss,sass,less,styl,stylus,postcss}",
      },
      {
        namespace: "Werkraum\\BootstrapFluidComponents\\Components",
        pathToComponents: resolve(__dirname, 'vendor/werkraum/bootstrap-fluid-components/Resources/Private/Components/'),
        componentPattern: "**/*.{js,ts,tsx,jsx,css,scss,sass,less,styl,stylus,postcss}",
      }
    ]),
  ]
})
