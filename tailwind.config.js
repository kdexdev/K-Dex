/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/js/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      minWidth: (theme) => ({
        ...theme('width'),
      }),
      minHeight: (theme) => ({
        ...theme('height'),
      }),
      colors: {
        // Grayscale Design palette:
        // https://grayscale.design/app?lums=93.88,75.81,61.02,48.49,37.90,30.08,17.48,12.88,7.73,5.00,1.48&palettes=%23463c34,%23e41f05,%23ef7009,%23e6ce14,%2334ab2b,%230e72dd,%238558e6,%23f25fad&filters=0|-3.0,0|0,0|0,0|0,0|0,0|0,0|0,0|0&names=grey,red,orange,yellow,green,blue,indigo,pink&labels=,,,,,,,
        // Color swatches
        "grayscale": {
          50:  "#f8f8f8", // 93.88
          100: "#e2e2e2", // 75.81
          200: "#cdcdcd", // 61.06
          300: "#b9b9b9", // 48.49
          400: "#a6a6a6", // 37.90
          500: "#959595", // 30.00
          600: "#747474", // 17.50
          700: "#646464", // 12.88
          800: "#4f4f4f", // 7.73
          900: "#3f3f3f", // 5.00
          950: "#202020"  // 1.48
        },
        "grey": {
          DEFAULT: "#a6a6a6", // grayscale-400
          50: "#f8f8f8",
          100: "#e2e2e2",
          200: "#cdcdcd",
          300: "#b9b9b9",
          400: "#a6a6a6",
          500: "#989592",
          600: "#7a736d",
          700: "#6d635b",
          800: "#574d45",
          900: "#473d35",
          950: "#24201c"
        },
        "red": {
          DEFAULT:"#e41f05", // red-600
          50:  "#fff6f5",
          100: "#fedad5",
          200: "#fdbeb5",
          300: "#fda195",
          400: "#fc8272",
          500: "#fb634f",
          600: "#e41f05",
          700: "#c71b04",
          800: "#9d1503",
          900: "#801103",
          950: "#450902"
        },
        "orange": {
          DEFAULT:"#ef7109", // orange-500
          50:  "#fef7f1",
          100: "#fddcc1",
          200: "#fbc293",
          300: "#f9a662",
          400: "#f7892f",
          500: "#ef7109",
          600: "#bb5807",
          700: "#a24c06",
          800: "#7f3c05",
          900: "#673004",
          950: "#361902"
        },
        "yellow": {
          DEFAULT:"#e6ce14", // yellow-200
          50:  "#fcf9e0",
          100: "#f3e474",
          200: "#e6ce14",
          300: "#d0ba12",
          400: "#baa610",
          500: "#a8960f",
          600: "#83750b",
          700: "#71650a",
          800: "#594f08",
          900: "#474006",
          950: "#252103"
        },
        "green": {
          DEFAULT:"#34ab2b", // green-500
          50:  "#f0fbef",
          100: "#bfeebb",
          200: "#8de187",
          300: "#55d24c",
          400: "#39be30",
          500: "#34ab2b",
          600: "#288521",
          700: "#23741d",
          800: "#1b5b17",
          900: "#164912",
          950: "#0b260a"
        },
        "blue": {
          DEFAULT:"#0e72dd", // blue-600
          50:  "#f4f9fe",
          100: "#cee4fc",
          200: "#abd1fa",
          300: "#86bdf7",
          400: "#63aaf5",
          500: "#4398f3",
          600: "#0e72dd",
          700: "#0c63c0",
          800: "#0a4e97",
          900: "#083f7a",
          950: "#042140"
        },
        "indigo": {
          DEFAULT:"#8457e6", // indigo-600
          50:  "#f9f7fe",
          100: "#e7defa",
          200: "#d5c6f6",
          300: "#c4aef3",
          400: "#b397ef",
          500: "#a482ec",
          600: "#8457e6",
          700: "#7440e2",
          800: "#581fd2",
          900: "#4719aa",
          950: "#260e5b"
        },
        "pink": {
          DEFAULT:"#f25fad", // pink-500
          50:  "#fef6fa",
          100: "#fcd8eb",
          200: "#f9bbdc",
          300: "#f79dcd",
          400: "#f57ebd",
          500: "#f25fad",
          600: "#de127f",
          700: "#c1106e",
          800: "#990c57",
          900: "#7d0a47",
          950: "#440527"
        },

        // 'Utility' color names
        'white': '#fef7f1',
        'black': '#0d0500',
        'light': '#e2e2e2', // grey-100
        'dark': '#24201c', // grey-950

        'primary': '#F77711',
        'secondary': '#a24c06', // orange-700
        'info': '#7440e2', // indigo-700
        'success': '#23741d', // green-700
        'warning': '#71650a', // yellow-700
        'danger': '#c71b04' // red-700
      }
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
  ]
}
