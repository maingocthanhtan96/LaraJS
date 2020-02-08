module.exports = {
  overrides: [
    {
      files: '*.{js,vue}',
      options: {
        printWidth: 120,
        singleQuote: true,
        semi: true,
        tabWidth: 2,
        trailingComma: 'none',
      },
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
        proseWrap: 'never',
      },
    },
  ],
};
