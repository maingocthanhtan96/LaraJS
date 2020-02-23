module.exports = {
  overrides: [
    {
      files: '*.{js,vue}',
      options: {
        arrowParens: 'avoid',
        bracketSpacing: true,
        htmlWhitespaceSensitivity: 'ignore',
        insertPragma: false,
        jsxBracketSameLine: true,
        jsxSingleQuote: true,
        printWidth: 80,
        proseWrap: 'always',
        quoteProps: 'as-needed',
        requirePragma: false,
        semi: true,
        singleQuote: true,
        tabWidth: 2,
        trailingComma: 'es5',
        useTabs: false,
        vueIndentScriptAndStyle: false
      }
    },
    {
      files: '*.{php}',
      options: {
        printWidth: 120,
        tabWidth: 4,
        useTabs: false,
        semi: false,
        singleQuote: false,
        trailingComma: 'all',
        bracketSpacing: true,
        jsxBracketSameLine: false,
        arrowParens: 'avoid',
        proseWrap: 'never'
      }
    }
  ]
};
