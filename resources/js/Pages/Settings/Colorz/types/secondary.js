export default {
    name: 'secondary',

    makeGlobal: true,
    scheme: "hsl",
    numColors: 21,
    colorz: {
        backgroundColor: {
            prefix: 'secondary-bg-',
            property: ' background-color: '
        },
        color: {
            prefix: 'secondary-text-',
            property: ' color: '
        },
        borderColor: { 
            prefix: 'secondary-border-',
            property: ' border-color: '     
        },
        fill: {
            prefix: 'secondary-fill-',
            property: ' fill: '
        },
        stroke: {
            prefix: 'secondary-stroke-',
            property: ' stroke: '
        },
        // outline: {
        //     prefix: 'secondary-outline-',
        //     property: 'outline-color:'
        // },
        // caret: {
        //     prefix: 'secondary-caret-',
        //     property: 'caret-color:'
        // },
        // accent: {
        //     prefix: 'secondary-accent-',
        //     property: 'accent-color:'
        // },
        // textDecorationColor: {
        //     prefix: 'secondary-txtdec-',
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
        start: 60,
        end: 60,
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
    }
}