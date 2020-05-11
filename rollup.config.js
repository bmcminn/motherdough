import commonjs from '@rollup/plugin-commonjs'
import resolve from '@rollup/plugin-node-resolve'
import livereload from 'rollup-plugin-livereload'
import svelte from 'rollup-plugin-svelte'


const IS_PROD = !process.env.ROLLUP_WATCH


export default {
    input: 'src/client/main.js',
    output: {
        compact: IS_PROD,
        file: 'public_html/js/bundle.js',
        format: 'iife',
        sourcemap: !IS_PROD,
    },
    watch: {
        clearScreen: false,
    },
    plugins: [
        svelte({
            dev: !IS_PROD,
            css: (css) => css.write('public_html/css/main.css'),
        }),
        resolve({
            browser: true,
            dedupe: [
                'svelte'
            ],
        }),
        commonjs(),
        !IS_PROD && livereload('public_html'),
    ],
}
