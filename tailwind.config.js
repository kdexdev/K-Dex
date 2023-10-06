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
        'orange': {
          DEFAULT: '#F77711',
          50: '#FCFCB4',
          100: '#FCF7A5',
          200: '#FBE987',
          300: '#FAD56A',
          400: '#F9BB4C',
          500: '#F89C2F',
          600: '#F77711',
          700: '#CE4B07',
          800: '#9D2A05',
          900: '#6B1304',
          950: '#530A03'
        },
        'red': {
          DEFAULT: '#C71802',
          50: '#FEF3B0',
          100: '#FEEDA1',
          200: '#FEDA83',
          300: '#FDC265',
          400: '#FDA446',
          500: '#FD8028',
          600: '#FD550A',
          700: '#E53202',
          800: '#C71802',
          900: '#950301',
          950: '#6A0105'
        },
        'green': {
          DEFAULT: '#549F00',
          50: '#F7F2B6',
          100: '#F8F58C',
          200: '#EDF85E',
          300: '#D4F533',
          400: '#B2F10A',
          500: '#90E600',
          600: '#64BD00',
          700: '#3D8F00',
          800: '#236C00',
          900: '#135200',
          950: '#0C4600'
        },
        'blue': {
          DEFAULT: '#0C63C0',
          50: '#DDFDF6',
          100: '#B7FAF0',
          200: '#8CF8F6',
          300: '#61E4F5',
          400: '#36C3F2',
          500: '#0F97EB',
          600: '#0C63C0',
          700: '#0A3E9E',
          800: '#09258B',
          900: '#08127D',
          950: '#0A0B57'
        },
        'indigo': {
          DEFAULT: '#8457E5',
          50: '#EFF3FD',
          100: '#E0E6FA',
          200: '#C1C5F5',
          300: '#A6A2F1',
          400: '#9683EC',
          500: '#8457E5',
          600: '#7B2BDE',
          700: '#781CBA',
          800: '#6B168D',
          900: '#550F61',
          950: '#460B4B'
        },
      }
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
  ]
}
