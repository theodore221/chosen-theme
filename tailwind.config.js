/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/blocks/**/*.{js,php}',
    './parts/**/*.html',
    './templates/**/*.html',
    './inc/**/*.php',
    './functions.php',
  ],
  theme: {
    extend: {
      colors: {
        'chosen-navy':   '#0B0A55',
        'chosen-royal':  '#4071AC',
        'chosen-gold':   '#EDA90C',
        'chosen-gold-600': '#C8890A',
        'chosen-red':    '#F71A1D',
        'chosen-orange': '#FE4E0E',
        'chosen-yellow': '#EBC903',
        'chosen-teal':   '#37BCB1',
        'chosen-paper':  '#FAF8F3',
        // Soft-tint layer — borrowed from the 2026 poster sun rays. Pair with navy
        // text. Use as section surfaces, not as accents on saturated brand colours.
        'chosen-cream':  '#F4ECDC',
        'chosen-sage':   '#BFD2B6',
        'chosen-sky':    '#C7DCE8',
        'chosen-aqua':   '#C5E5DD',
        'chosen-sun':    '#FAE2A3',
        'chosen-coral':  '#F4B7A0',
        'chosen-black':  '#0A0A0A',
      },
      fontFamily: {
        sans:    ["'Work Sans'", 'sans-serif'],
        display: ["'Anton'",    'sans-serif'],
        bebas:   ["'Bebas Neue'", 'sans-serif'],
      },
      letterSpacing: {
        eyebrow: '0.18em',
        widest:  '0.22em',
      },
      transitionTimingFunction: {
        // Tailwind prepends `ease-` automatically, so keys are bare names.
        // Class names: `ease-out-quart`, `ease-movement`.
        'out-quart': 'cubic-bezier(0.22, 0.61, 0.36, 1)',
        'movement':  'cubic-bezier(0.4, 0, 0.6, 1)',
      },
      boxShadow: {
        'chosen-sm': '0 1px 3px rgba(11, 10, 85, 0.12)',
        'chosen-md': '0 4px 12px rgba(11, 10, 85, 0.16)',
        'chosen-lg': '0 8px 32px rgba(11, 10, 85, 0.20)',
        'glow-gold': '0 0 0 4px rgba(237, 169, 12, 0.35)',
      },
      maxWidth: {
        content: '720px',
        wide:    '1200px',
      },
    },
  },
  plugins: [],
};
