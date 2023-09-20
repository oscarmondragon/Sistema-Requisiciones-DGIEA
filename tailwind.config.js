import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
const colors = require("tailwindcss/colors");

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                verde: "#62836C",
                dorado: "#9D9361", //titulos
                textos_generales: "#4D4D4D",
                btn_vobo: "#3B5A60",
                btn_cancelar: "#D9AC52",
                rojo: "#E86562",
                blanco: "#F1F2F2",
                trasnparente_botones: "rgb(255, 255, 255, .3)",
            },
        },
    },

    plugins: [forms],
};
