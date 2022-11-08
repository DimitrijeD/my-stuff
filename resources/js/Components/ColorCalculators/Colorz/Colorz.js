export class Colorz {
    constructor(params){
        this.START_TO_END = 'to';
        this.INC         = 'inc'; 
        this.DEC         = 'dec'; 

        this.numColors = parseInt(params.numColors)
        
        this.colors = [] 
        this.classes = []

        this.testing = params?.testing ? true : false
        this.makeGlobal = params?.makeGlobal ? true : false

        params.hue.start = parseInt(params.hue.start)
        params.hue.end   = parseInt(params.hue.end)
        params.hue.inc   = parseInt(params.hue.inc)
        params.hue.dec   = parseInt(params.hue.dec)
        params.hue.boundaries.min = parseInt(params.hue.boundaries.min)
        params.hue.boundaries.max = parseInt(params.hue.boundaries.max)

        params.saturation.start = parseInt(params.saturation.start)
        params.saturation.end   = parseInt(params.saturation.end)
        params.saturation.inc   = parseInt(params.saturation.inc)
        params.saturation.dec   = parseInt(params.saturation.dec)
        params.saturation.boundaries.min = parseInt(params.saturation.boundaries.min)
        params.saturation.boundaries.max = parseInt(params.saturation.boundaries.max)

        params.lightness.start = parseInt(params.lightness.start)
        params.lightness.end   = parseInt(params.lightness.end)
        params.lightness.inc   = parseInt(params.lightness.inc)
        params.lightness.dec   = parseInt(params.lightness.dec)
        params.lightness.boundaries.min = parseInt(params.lightness.boundaries.min)
        params.lightness.boundaries.max = parseInt(params.lightness.boundaries.max)

        this.configElement(params.hue)
        this.configElement(params.saturation)
        this.configElement(params.lightness)

        this.colorz = params.colorz

        this.globalClasses = {}
        this.prepareGlobalClassesContainer()
    }

    /**
     * Poulates object with key: [] which is just a placeholder for classes which have been created
     * 
     * Usefull for testing purposes only. Has no effect on global classes
     */
    prepareGlobalClassesContainer(){
        for(let j in this.colorz){
            this.globalClasses[j] = []
        }
    }

    configElement(elementParams){
        this[elementParams.name] = {
            max: 360,
            start: elementParams.start, 
            offset: elementParams?.use
                ? this.elementOffsetType(elementParams)
                : this.elementDefaultOffsetType(elementParams),
            overflow: elementParams?.overflow 
                ? elementParams.overflow 
                : false,
            boundaries: {
                min: elementParams.boundaries.min,
                max: elementParams.boundaries.max,
            }
        }
    }

    elementOffsetType(element){
        switch(element.use){
            case this.START_TO_END:
                return this.elementUsesEnd(element)

            case this.INC:
                return element.inc

            case this.DEC:
                return element.dec

            default:
                // throw exception
                return 0
        }
    }

    elementUsesEnd(element){
        if(element.start == element.end) {
            return 0    
        } else {
            return this.overflowOffset(
                element.start, 
                element.end, 
                element.boundaries.max, 
                this.numColors, 
                element?.direction 
                    ? element.direction 
                    : ''
            )
        }
    }

    elementDefaultOffsetType(element){
        if(element?.end) return this.elementUsesEnd(element)
        if(element?.inc) return element.inc
        if(element?.dec) return element.dec

        // @todo throw exception
        return 0
    }

    make(){
        let hueSum        = this.hue.start
        let saturationSum = this.saturation.start
        let lightnessSum  = this.lightness.start

        // first color is set to what ever was provided by user, while rest is calculated
        this.colors.push([hueSum, saturationSum, lightnessSum]) 

        for(let i = 0; i < this.numColors - 1; i++){
            hueSum        = this.addOffset(hueSum,        this.hue.offset,        this.hue.boundaries.max,        this.hue.overflow)
            saturationSum = this.addOffset(saturationSum, this.saturation.offset, this.saturation.boundaries.max, this.saturation.overflow)
            lightnessSum  = this.addOffset(lightnessSum,  this.lightness.offset,  this.lightness.boundaries.max,  this.lightness.overflow) 

            this.colors.push([hueSum, saturationSum, lightnessSum])
        }

        if(!this.testing){
            this.createClasses()
        }

        if(this.makeGlobal){
            // this.replaceRules(this.makeColorz())
            this.globalRules = this.makeColorz()
        } else {
            this.globalRules = ''
        }

        return {
            classes: this.classes,
            globalClasses: this.globalClasses,
            globalRules: this.globalRules,
        }
    }

    /**
     * 
     * @param int start - Value of first color
     * @param int end - Value of first color
     * @param int max - Highest value for Hue || Saturation || Lightness
     * @param int numElements - How many colors to make 
     * @param string direction - 'left' for overflowing left, anything else for right (default)  
     * @returns 
     */
    overflowOffset(start, end, max, numElements, direction){
        if(direction == 'left'){
            if(start < end )
                return Math.round(((start + max - end) / (numElements - 1)) * 100) / 100 

            if(start > end )
                return Math.round(((start - end) / (numElements - 1)) * 100) / 100 

        } else {
            if(start < end )
                return Math.round(((end - start) / (numElements - 1)) * 100) / 100

            if(start > end )
                return Math.round(((max - start + end) / (numElements - 1)) * 100) / 100

            if(start == end )
                return Math.round( (max / (numElements - 1)) * 100 ) / 100
        }
    }

    addOffset(color, offset, max, canOverflow){
        let sum = color + offset
        sum = +(Math.round(sum + "e+2")  + "e-2")

        if(sum >= 0 && sum <= max) return sum

        if(sum > max){
            return canOverflow
                ? sum%max - 1
                : max
        }

        if(sum < 0){
            return canOverflow
                ? (max + 1) + sum
                : 0
        }

        // cant do anything, math is broken
        return color
    }

    // background-color: hsl(243, 73%, 72%)
    createClasses(){
        let cls = {}

        for(let i = 0; i < this.colors.length; i++){
            cls = {}
            cls['background-color'] = `hsl(${this.colors[i][0]}, ${this.colors[i][1]}%, ${this.colors[i][2]}%)`

            this.classes.push(cls)
        }
    }

    makeColorz(){
        let classes = ' '

        for(let i = 0; i < this.colors.length; i++){
            for(let j in this.colorz){
                this.globalClasses[j].push( this.rawClassName(this.colorz[j].prefix, i) )
                classes += this.makeOneClass(this.colorz[j].prefix, this.colorz[j].property ,i)
            }
        }

        return classes
    }


    rawClassName(className, index){
        return `${className}${index}`
    }

    addSelector(selector = '.'){
        return `${selector}`
    }

    clsName(prefix, index){
        return `${prefix}${index}`
    }

    addOpenBraces(){
        return ` { `
    }

    addHSL(i){
        return `hsl(${this.colors[i][0]}, ${this.colors[i][1]}%, ${this.colors[i][2]}%)`
    }

    addClosedBraces(){
        return ` } `
    }

    addclassProp(name){
        return ` ${name} `
    }

    makeOneClass(prefix, cssProperty, index){
        let cls = ' '

        cls += this.addSelector()
        cls += this.clsName(prefix, index)
        cls += this.addOpenBraces()
        cls += this.addclassProp(cssProperty)
        cls += this.addHSL(index)
        cls += this.addClosedBraces()

        return cls
    }

    //'.target {color: red} .anotherClass{ color: gray; }'
    static replaceRules(str){
        colorz.replace(str)
    }
}
