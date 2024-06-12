/** @type {import('tailwindcss').Config} */

module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    container: {
      center: true,
      padding: {
        DEFAULT: "1.25rem",
        sm: "2rem",
        md: "2.5rem",
        lg: "2rem",
        xl: "2rem",
        "2xl": "10rem",
      },
    },
    colors: {
      transparent: "transparent",
      current: "currentColor",
      white: "#FFFFFF",
      black: "#000000",
      red: {
        100: "#E63946",
        200: "#FCA5A5",
        600: "#EF4444",
      },
      success: {
        500: "#10B981",
      },
      primary: {
        100: "#E0F2FE",
        300: "#00b4d8",
        600: "#0876DD",
        900: "#03045e"
      },
      secondary: {
        600: "#29D2D3",
        100: "#CFFAFE",
      },
      darkness: {
        200: "#E3E3E5",
        400: "#D0D0D0",
        500: "#2B2B2B",
      },
      neutral: {
        900: "#111827",
        800: "#1F2937",
        700: "#374151",
        600: "#4B5563",
        500: "#6B7280",
        400: "#9CA3AF",
        300: "#D1D5DB",
        200: "#E5E7EB",
        100: "#F3F4F6",
        50: "#F9FAFB",
      },
    },
    fontFamily: {
      sans: ["General Sans", "sans-serif"],
    },
    fontSize: {
      "display-2xl": [
        "64px",
        {
          letterSpacing: "-0.02em",
          lineHeight: "80px",
        },
      ],
      "display-xl": [
        "56px",
        {
          letterSpacing: "-0.01em",
          lineHeight: "72px",
        },
      ],
      "display-lg": [
        "48px",
        {
          letterSpacing: "-0.01em",
          lineHeight: "60px",
        },
      ],
      "display-md": [
        "36px",
        {
          letterSpacing: "-0.01em",
          lineHeight: "48px",
        },
      ],
      "display-sm": [
        "30px",
        {
          letterSpacing: "-0.01em",
          lineHeight: "40px",
        },
      ],
      "display-xs": [
        "24px",
        {
          letterSpacing: "-0.01em",
          lineHeight: "32px",
        },
      ],
      "body-xl": [
        "20px",
        {
          letterSpacing: "0.01em",
          lineHeight: "28px",
        },
      ],
      "body-lg": [
        "18px",
        {
          letterSpacing: "0.01em",
          lineHeight: "28px",
        },
      ],
      "body-md": [
        "16px",
        {
          letterSpacing: "0.01em",
          lineHeight: "24px",
        },
      ],
      "body-sm": [
        "14px",
        {
          letterSpacing: "0.01em",
          lineHeight: "20px",
        },
      ],
      "body-xs": [
        "12px",
        {
          letterSpacing: "0.01em",
          lineHeight: "16px",
        },
      ],
    },
    boxShadow: {
      xs: "0px 2px 8px -3px rgba(28, 44, 64, 0.16)",
      sm: "0px 4px 12px -3px rgba(28, 44, 64, 0.1)",
      md: "0px 8px 16px -4px rgba(28, 44, 64, 0.08)",
      lg: "0px 10px 20px rgba(28, 44, 64, 0.08)",
      xl: "4px 12px 24px rgba(28, 44, 64, 0.08)",
      "2xl": "8px 16px 48px rgba(28, 44, 64, 0.12)",
    },
    extend: {
      spacing: {
        18: "72px",
        22: "88px",
        30: "120px",
      },
    },
  },
  plugins: [
    require('flowbite/plugin') ({
      charts: true,
    }),
  ],
};
