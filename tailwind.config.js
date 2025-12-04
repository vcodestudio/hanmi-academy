module.exports = {
  content: [
    "./**/*.php",
    "./src/js/**/*.js",
    "./tasks/**/*.html",
  ],
  theme: {
    screens: {
      // Match LESS @mobile (max-width: 765px)
      m: { max: "765px" },
    },
    extend: {
      colors: {
        ascent: { // 5fb800
          DEFAULT: "#5fb800",
        },
      },
    },
  },
  corePlugins: {
    preflight: false,
  },
  plugins: [],
};


