const plugin = require('tailwindcss/plugin');

module.exports = plugin.withOptions(
  function (options = {}) {
    const prefix = options.variablePrefix || 'shiki';
    return function ({ addComponents, theme }) {
      // Optional: Add a base class for the wrapper if they want standard styling
    };
  },
  function (options = {}) {
    const prefix = options.variablePrefix || 'shiki';

    return {
      theme: {
        extend: {
          colors: {
            [prefix]: {
              bg: `var(--${prefix}-bg)`,
              fg: `var(--${prefix}-fg)`,
              'token-comment': `var(--${prefix}-token-comment)`,
              'token-string': `var(--${prefix}-token-string)`,
              'token-keyword': `var(--${prefix}-token-keyword)`,
              'token-function': `var(--${prefix}-token-function)`,
              'token-class': `var(--${prefix}-token-class)`,
              'token-constant': `var(--${prefix}-token-constant)`,
              'token-punctuation': `var(--${prefix}-token-punctuation)`,
              'token-variable': `var(--${prefix}-token-variable)`,
            },
          },
        },
      },
    };
  }
);
