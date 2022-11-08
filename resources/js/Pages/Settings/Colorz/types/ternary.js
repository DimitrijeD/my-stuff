export default {
    name: 'ternary',

    makeGlobal: true,
    scheme: "hsl",
    numColors: 21,
    colorz: {
        backgroundColor: {
            prefix: 'ternary-bg-',
            property: ' background-color: '
        },
        color: {
            prefix: 'ternary-text-',
            property: ' color: '
        },
        borderColor: { 
            prefix: 'ternary-border-',
            property: ' border-color: '     
        },
        fill: {
            prefix: 'ternary-fill-',
            property: ' fill: '
        },
        stroke: {
            prefix: 'ternary-stroke-',
            property: ' stroke: '
        },
        // outline: {
        //     prefix: 'ternary-outline-',
        //     property: 'outline-color:'
        // },
        // caret: {
        //     prefix: 'ternary-caret-',
        //     property: 'caret-color:'
        // },
        // accent: {
        //     prefix: 'ternary-accent-',
        //     property: 'accent-color:'
        // },
        // textDecorationColor: {
        //     prefix: 'ternary-txtdec-',
        //     property: 'text-decoration-color:'
        // },
    },

    hue:{
        name: "hue", 
        slider: {
            start: {
                min: 0,
                max: 360
            },
            to: {
                min: 0,
                max: 360
            },
            inc:{
                min: 0,
                max: 359
            },
            dec: {
                min: 0,
                max: 359
            },
        },
        boundaries: {
            min: 0, max: 360
        },
        start: 133,
        end: 133,
        inc: 0,
        dec: 0,
        overflow: false,
        use: 'to',
    },

    saturation:{
        name: "saturation", 
        slider: {
            start: {
                min: 0,
                max: 100
            },
            to: {
                min: 0,
                max: 100
            },
            inc:{
                min: 0,
                max: 99
            },
            dec: {
                min: 0,
                max: 99
            },
        },
        boundaries: {
            min: 0, max: 100
        },
        start: 100,
        end: 100,
        inc: 0,
        dec: 0,
        overflow: false,
        use: 'to',
    },

    lightness:{
        name: "lightness", 
        slider: {
            start: {
                min: 0,
                max: 100
            },
            to: {
                min: 0,
                max: 100
            },
            inc:{
                min: 0,
                max: 99
            },
            dec: {
                min: 0,
                max: 99
            },
        },
        boundaries: {
            min: 0, max: 100
        },
        start: 3,
        end: 90,
        inc: 0,
        dec: 0,
        overflow: false,
        use: 'to',
    },
}