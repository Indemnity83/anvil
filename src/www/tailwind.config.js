module.exports = {
  theme: {
    extend: {
      colors: require('@ky-is/tailwind-color-palette')('#46607F', {grayscale: true, ui: true}),
    },
  },
  variants: {},
  plugins: [
    require('@tailwindcss/ui'),
  ],
}
